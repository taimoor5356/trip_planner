<?php

namespace App\Http\Controllers;

use App\Models\Region;
use DataTables;
use App\Models\Town;
use App\Models\RegionSeason;
use App\Models\Season;
use App\Models\SeasonType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RegionSeasonController extends Controller
{
    public $headerTitle = 'Region Season';
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
            ->addColumn('region_id', function ($row) {
                return ucwords($row->region?->name);
            })
            ->addColumn('season_id', function ($row) {
                return ucwords($row->season?->name);
            })
            ->addColumn('no_of_days', function ($row) {
                return ucwords($row->no_of_days);
            })
            // ->addColumn('mode_of_travel', function ($row) {
            //     return ucwords($row->mode_of_travel == 1 ? 'By Road' : 'By Air');
            // })
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
                        $btns .= '<a class="btn btns m-0 p-1" data-region-season-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-region-season" data-region-season-id="'.$row->id.'" href="#">
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
            $records = RegionSeason::orderBy('id', 'desc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = 'Region Seasons List';
        return view('admin.region_seasons.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = 'Add New';
        $data['regions'] = Region::where('status', 1)->get();
        $data['seasons'] = Season::where('status', 1)->get();
        return view('admin.region_seasons.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'region_id' => 'required',
            'season_id' => 'required'
        ]);
        $regionSeason = new RegionSeason();
        return $this->storeRegionSeason($regionSeason, $request);
        // return redirect('admin/region-seasons/list')->with('success', 'RegionSeason added successfully');
    }

    public function storeRegionSeason($regionSeason, $request) {
        if (!RegionSeason::where('region_id', $request->region_id)->where('season_id', $request->season_id)->where('no_of_days', $request->no_of_days)->where('mode_of_travel', $request->mode_of_travel)->exists()) {
            $regionSeason->region_id = $request->region_id;
            $regionSeason->season_id = $request->season_id ?? null;
            $regionSeason->no_of_days = $request->no_of_days ?? null;
            // $regionSeason->mode_of_travel = $request->mode_of_travel ?? null;
            $regionSeason->status = $request->status ?? null;
            $regionSeason->save();
            return redirect('admin/region-seasons/list')->with('success', 'Successfully saved');
        }
        return redirect()->back()->with('error', 'Already exists');
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
        $data['header_title'] = 'Edit Region Season';
        $data['record'] = RegionSeason::find($id);
        $data['regions'] = Region::where('status', 1)->get();
        $data['seasons'] = Season::where('status', 1)->get();
        return view('admin.region_seasons.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        request()->validate([
            'region_id' => 'required',
            'season_id' => 'required'
        ]);
        $regionSeason = RegionSeason::find($id);
        return $this->storeRegionSeason($regionSeason, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = RegionSeason::where('id', $request->data_id)->first();
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
