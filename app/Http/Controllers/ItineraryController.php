<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\ActivityType;
use App\Models\City;
use DataTables;
use App\Models\Region;
use App\Models\Itinerary;
use App\Models\ItineraryActivity;
use App\Models\ItineraryDayWisePlan;
use App\Models\Origin;
use App\Models\Season;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ItineraryController extends Controller
{
    public $headerTitle = 'Itinerary';
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
            ->addColumn('head_line', function ($row) {
                return ucwords($row->head_line);
            })
            ->addColumn('tag_line', function ($row) {
                return ucwords($row->tag_line);
            })
            ->addColumn('mode_of_travel', function ($row) {
                return ucwords($row->mode_of_travel == 1 ? "By Road" : "By Air");
            })
            ->addColumn('origin', function ($row) {
                return ucwords($row->origin?->name);
                // $ids = json_decode($row->season_availability, true);
                // $names = Season::whereIn('id', $ids)->pluck('name')->toArray();
                // return implode(', ', array_map('ucwords', $names));
            })
            ->addColumn('destination', function ($row) {
                return ucwords($row->destination?->name);
                // $ids = json_decode($row->season_availability, true);
                // $names = Season::whereIn('id', $ids)->pluck('name')->toArray();
                // return implode(', ', array_map('ucwords', $names));
            })
            ->addColumn('trip_duration', function ($row) {
                return ucwords(str_replace('_', ' ', $row->trip_duration));
                // $ids = json_decode($row->season_availability, true);
                // $names = Season::whereIn('id', $ids)->pluck('name')->toArray();
                // return implode(', ', array_map('ucwords', $names));
            })
            ->addColumn('status', function ($row) {
                return ucwords($row->status == 1 ? 'Active' : 'In Active');
            })
            ->addColumn('created_at', function ($row) {
                return ucwords($row->created_at);
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-itinerary-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-itinerary" data-itinerary-id="'.$row->id.'" href="#">
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
            ->rawColumns(['sr_no', 'checkbox', 'name', 'role', 'group', 'actions'])
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
            $records = Itinerary::orderBy('id', 'desc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = 'Itineraries List';
        return view('admin.itineraries.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = 'Add New';
        $data['cities'] = City::where('status', 1)->get();
        $data['seasonsAvailability'] = Season::where('status', 1)->get();
        $data['activityTypes'] = ActivityType::where('status', 1)->get();
        $data['regions'] = Region::where('status', 1)->get();
        $data['origins'] = Origin::where('status', 1)->get();
        return view('admin.itineraries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $itinerary = new Itinerary();
        $this->storeItinerary($itinerary, $request);
        return redirect('admin/itineraries/list')->with('success', 'Itinerary added successfully');
    }

    public function storeItinerary($itinerary, $request) {
        try {
            $itinerary->head_line = $request->head_line;
            $itinerary->tag_line = $request->tag_line ?? null;
            $itinerary->mode_of_travel = $request->mode_of_travel ?? null;
            $itinerary->origin_id = $request->starting_point ?? null;
            $itinerary->destination_id = $request->destination ?? null;
            $itinerary->trip_duration = $request->trip_duration ?? null;
            // $itinerary->days = $request->days ?? null;
            // $itinerary->nights = $request->nights ?? null;
            $itinerary->trip_id = 'Trip-' . time();
            $itinerary->save();
            Helpers::storeImage($request, \App\Models\ItineraryImage::class, $itinerary, 'itinerary_id', 'itineraries');
            ItineraryDayWisePlan::where('itinerary_id', $itinerary->id)->delete();
            foreach ($request->days as $key => $data) {
                ItineraryDayWisePlan::create([
                    'itinerary_id' => $itinerary->id,
                    // 'day' => $request->days[$key],
                    'origin' => $request->origins[$key]['origin'],
                    'destination_id' => $request->city_ids[$key]['city_id'],
                    'landmarks' => json_encode($request->days[$key]['landmarks'])
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
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
        $data['header_title'] = 'Edit Itinerary';
        $data['record'] = Itinerary::find($id);
        $data['regions'] = Region::where('status', 1)->get();
        $data['seasonsAvailability'] = Season::where('status', 1)->get();
        $data['activityTypes'] = ActivityType::where('status', 1)->get();
        return view('admin.itineraries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $itinerary = Itinerary::find($id);
        $this->storeItinerary($itinerary, $request);
        return redirect('admin/itineraries/list')->with('success', 'Itinerary updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = Itinerary::where('id', $request->data_id)->first();
        if (isset($accommodation)) {
            $accommodation->delete();
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function importData(Request $request)
    {     
dd('Under Construction');   
        try {
            $originalTimeLimit = ini_get('max_execution_time');
            set_time_limit(7200);
            gc_disable();
            Excel::import(new AccomodationImport(), $request->file('file'));

            exit;
            gc_enable();
            set_time_limit($originalTimeLimit);

            return response()->json(['status' => 'success', 'message' => 'Data imported successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong. ' . $e->getMessage()], 200);
        }
    }
}
