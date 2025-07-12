<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use App\Models\LandMark;
use DataTables;
use App\Models\Region;
use App\Models\LandMarkSeason;
use App\Models\Season;
use App\Models\SeasonType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LandMarkSeasonController extends Controller
{
    public $headerTitle = 'Land Mark Season';
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
            ->addColumn('landmark_name', function ($row) {
                return ucwords($row->landmark?->name);
            })
            ->addColumn('season_name', function ($row) {
                return ucwords($row->season?->name);
            })
            // ->addColumn('location', function ($row) {
            //     return ucwords($row->location);
            // })->addColumn('season_availability', function ($row) {
            //     $ids = json_decode($row->season_availability, true);
            //     $names = Season::whereIn('id', $ids)->pluck('name')->toArray();
            //     return implode(', ', array_map('ucwords', $names));
            // })
            // ->addColumn('activity_ids', function ($row) {
            //     $ids = json_decode($row->activity_ids, true);
            //     $names = ActivityType::whereIn('id', $ids)->pluck('name')->toArray();
            //     return implode(', ', array_map('ucwords', $names));
            // })
            ->addColumn('status', function ($row) {
                return ucwords($row->status);
            })
            ->addColumn('created_at', function ($row) {
                return ucwords($row->created_at);
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-land-mark-season-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-land-mark-season" data-land-mark-season-id="'.$row->id.'" href="#">
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
            $records = LandMarkSeason::orderBy('id', 'asc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = $this->headerTitle;
        return view('admin.land_mark_season.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = $this->headerTitle;
        $data['landMarks'] = LandMark::where('status', 1)->get();
        $data['seasonsAvailability'] = Season::where('status', 1)->get();
        $data['seasonTypes'] = SeasonType::where('status', 1)->get();
        return view('admin.land_mark_season.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'landmark_id' => 'required',
            'season_availability' => 'required'
        ]);
        $landMark = new LandMarkSeason();
        return $this->storeLandMarkSeason($landMark, $request);
    }

    public function storeLandMarkSeason($landMark, $request) {
        LandMarkSeason::whereIn('season_id', $request->season_availability)->where('landmark_id', $request->landmark_id)->delete();
        foreach ($request->season_availability as $seasonAvailability) {
            $landMark->landmark_id = $request->landmark_id;
            $landMark->season_id = $seasonAvailability;
            $landMark->save();
        }
        return redirect('admin/land-mark-seasons/list')->with('success', 'Saved Successfully');
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
        $data['header_title'] = $this->headerTitle;
        $data['record'] = LandMarkSeason::find($id);
        $data['landMarks'] = LandMark::where('status', 1)->get();
        $data['seasonsAvailability'] = Season::where('status', 1)->get();
        return view('admin.land_mark_season.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        return redirect('admin/land-mark-seasons/list')->with('error', 'Please wait');
        $landMark = LandMarkSeason::find($id);
        return $this->storeLandMarkSeason($landMark, $request);
        return redirect('admin/land-marks/list')->with('success', 'LandMarkSeason updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = LandMarkSeason::where('id', $request->data_id)->first();
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
