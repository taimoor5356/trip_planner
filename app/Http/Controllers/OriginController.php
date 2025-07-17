<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Imports\OriginImport;
use App\Models\Origin;
use App\Models\OriginDestination;
use App\Models\Region;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class OriginController extends Controller
{
    public $headerTitle = 'Origin';
    public function datatables($request, $records, $trashed = null)
    {
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $records = $records->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%");
            });
        }
        $totalRecords = $records->count(); // Get the total number of records for pagination
        $data = $records->skip($request->start)
            ->take($request->length)
            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('sr_no', function ($row) {
                return $row->id;
            })
            ->addColumn('image', function ($row) {
                $images = (!empty($row->images) && count($row->images) > 0) ? $row->images : null;
                $image = '';
                if (!empty($images) || !is_null($images)) {
                    $image = $images[0]->image;
                }
                return '<img src='.asset('imgs/origins/'.$image).' height="25px">';
                // $firstImage = isset($images[0]) ? trim($images[0]['image']) : null;

                // $imagePath = $firstImage ? asset('imgs/origins/' . $firstImage) : '';
                return $images;
                // return '<img src="' . $imagePath . '" height="25px">';
            })
            ->addColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('status', function ($row) {
                return ucwords($row->status == 1 ? 'Active' : 'In Active');
            })
            ->addColumn('destination_names_list', function ($row) {
                return ucwords($row->destination_names_list);
            })
            ->addColumn('created_at', function ($row) {
                return ucwords($row->created_at);
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-origin-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-origin" data-origin-id="'.$row->id.'" href="#">
                                <i class="align-middle text-danger" data-feather="trash-2">
                                </i>
                            </a>
                        </div>';
                    } else {
                        $btns.= '<a class="btn btns m-0 p-1" href="restore/' . $row->id . '">
                                <i class="align-middle text-success" data-feather="refresh-cw">
                                </i>
                            </a>
                        </div>';
                    }
                return $btns;
            })
            ->rawColumns(['sr_no', 'checkbox', 'name', 'image', 'group', 'actions'])
            ->setTotalRecords($totalRecords)
            ->setFilteredRecords($totalRecords) // For simplicity, same as totalRecords
            ->skipPaging()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $records = Origin::with('images')->orderBy('id', 'desc');
            return $this->datatables($request, $records);
        }
        $data['origins'] = Origin::with('images')->orderBy('id', 'desc')->get();
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = $this->headerTitle;
        return view('admin.origins.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = $this->headerTitle;
        $data['destinations'] = Region::where('status', 1)->get();
        return view('admin.origins.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'name' => 'required|string'
        ]);
        $origin = new Origin();
        $this->storeOrigin($origin, $request);
        return redirect('admin/origins/list')->with('success', 'Origin added successfully');
    }

    public function storeOrigin($origin, $request) {
        $origin->name = $request->name;
        $origin->destination_ids = null;
        $origin->status = $request->status ?? 0;
        $origin->save();
        Helpers::storeImage($request, \App\Models\OriginImage::class, $origin, 'origin_id', 'origins');
        
        $originId = $origin->id; // coming from hidden field or manually set

        $roadIds = $request->input('by_road_destination_ids', []);
        $airIds  = $request->input('by_air_destination_ids', []);

        $roadDays = $request->input('destination_days_by_road', []);
        $airDays  = $request->input('destination_days_by_air', []);

        // Clear existing origin-destination records
        OriginDestination::where('origin_id', $origin->id)->delete();

        // Save by road destinations
        foreach ($roadIds as $destinationId) {
            $days = $roadDays[$destinationId] ?? [];

            foreach ($days as $day) {
                OriginDestination::create([
                    'origin_id' => $originId,
                    'destination_id' => $destinationId,
                    'mode_of_travel' => 1, // by road
                    'days_nights' => str_replace(' ', '_', strtolower($day)),
                ]);
            }

            // If no days entered, still save one record
            if (empty($days)) {
                OriginDestination::create([
                    'origin_id' => $originId,
                    'destination_id' => $destinationId,
                    'mode_of_travel' => 1,
                    'days_nights' => null,
                ]);
            }
        }

        // Save by air destinations
        foreach ($airIds as $destinationId) {
            $days = $airDays[$destinationId] ?? [];

            foreach ($days as $day) {
                OriginDestination::create([
                    'origin_id' => $originId,
                    'destination_id' => $destinationId,
                    'mode_of_travel' => 2, // by air
                    'days_nights' => $day,
                ]);
            }

            if (empty($days)) {
                OriginDestination::create([
                    'origin_id' => $originId,
                    'destination_id' => $destinationId,
                    'mode_of_travel' => 2,
                    'days_nights' => null,
                ]);
            }
        }

        // OriginDestination::where('origin_id', $origin->id)->delete();
        // if (!empty($request->by_road)) {
        //     foreach ($request->by_road_destination_ids as $destination)
        //     {
        //         OriginDestination::create([
        //             'origin_id' => $origin->id,
        //             'destination_id' => $destination,
        //             'mode_of_travel' => $request->by_road ? 1 : 1
        //         ]);
        //     }
        // }
        // if (!empty($request->by_air)) {
        //     foreach ($request->by_air_destination_ids as $destination)
        //     {
        //         OriginDestination::create([
        //             'origin_id' => $origin->id,
        //             'destination_id' => $destination,
        //             'mode_of_travel' => $request->by_air ? 2 : 1
        //         ]);
        //     }
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // $data['header_title'] = $this->headerTitle;
        // $data['record'] = Origin::with('originDestinations.destinationRegion')->find($id);
        // $data['byRoadDestinations'] = isset($data) ? $data['record']->originDestinations->where('mode_of_travel', 1)->pluck('destination_id')->toArray() : null;
        // $data['byAirDestinations'] = isset($data) ? $data['record']->originDestinations->where('mode_of_travel', 2)->pluck('destination_id')->toArray() : null;
        // $data['destinations'] = Region::where('status', 1)->get();

        $data['header_title'] = $this->headerTitle;

        // Get the origin record with relationships
        $data['record'] = Origin::with('originDestinations.destinationRegion')->findOrFail($id);

        // Fetch destinations from originDestinations relation
        $roadRecords = $data['record']->originDestinations->where('mode_of_travel', 1);
        $airRecords  = $data['record']->originDestinations->where('mode_of_travel', 2);

        // Get destination IDs for Select2
        $data['byRoadDestinationIds'] = $roadRecords->pluck('destination_id')->unique()->values()->toArray();
        $data['byAirDestinationIds']  = $airRecords->pluck('destination_id')->unique()->values()->toArray();

        // Group days by destination_id
        $data['byRoadDestinations'] = [];
        foreach ($roadRecords as $item) {
            $data['byRoadDestinations'][$item->destination_id][] = $item->days_nights;
        }

        $data['byAirDestinations'] = [];
        foreach ($airRecords as $item) {
            $data['byAirDestinations'][$item->destination_id][] = $item->days_nights;
        }

        // All available destination options
        $data['destinations'] = Region::where('status', 1)->get();

        return view('admin.origins.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        request()->validate([
            'name' => 'required|string'
        ]);
        $origin = Origin::find($id);
        $this->storeOrigin($origin, $request);
        return redirect('admin/origins/list')->with('success', 'Origin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = Origin::where('id', $request->data_id)->first();
        if (isset($accommodation)) {
            $accommodation->delete();
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function importData(Request $request)
    {     
        try {
            $originalTimeLimit = ini_get('max_execution_time');
            set_time_limit(7200);
            gc_disable();
            Excel::import(new OriginImport(), $request->file('file'));

            exit;
            gc_enable();
            set_time_limit($originalTimeLimit);

            return response()->json(['status' => 'success', 'message' => 'Data imported successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong. ' . $e->getMessage()], 200);
        }
    }
}
