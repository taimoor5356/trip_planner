<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Imports\LandMarkImport;
use App\Models\ActivityType;
use App\Models\City;
use DataTables;
use App\Models\Region;
use App\Models\LandMark;
use App\Models\LandMarkActivity;
use App\Models\LandMarkSeason;
use App\Models\LandMarkSeasonActivity;
use App\Models\Season;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LandMarkController extends Controller
{
    public $headerTitle = 'Land Mark';
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
            ->addColumn('city_name', function ($row) {
                return ucwords($row->city?->name);
            })
            ->addColumn('landmark_name', function ($row) {
                return ucwords($row->name);
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
                return ucwords($row->status == 1 ? 'Active' : 'In Active');
            })
            ->addColumn('created_at', function ($row) {
                return ucwords($row->created_at);
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-land-mark-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-land-mark" data-land-mark-id="'.$row->id.'" href="#">
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
            $records = LandMark::orderBy('id', 'asc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = 'LandMarks List';
        return view('admin.land_marks.index', $data);
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
        return view('admin.land_marks.create', $data);
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
        $landMark = new LandMark();
        $this->storeLandMark($landMark, $request);
        return redirect('admin/land-marks/list')->with('success', 'LandMark added successfully');
    }

    public function storeLandMark($landMark, $request) {
        $landMark->name = $request->name;
        $landMark->city_id = $request->city_id ?? null;
        $landMark->location = $request->location ?? null;
        $landMark->season_availability = json_encode($request->season_availability) ?? null;
        $landMark->activity_ids = json_encode($request->activity_ids) ?? null;
        $landMark->status = $request->status ?? null;
        $landMark->save();
        Helpers::storeImage($request, \App\Models\LandMarkImage::class, $landMark, 'land_mark_id', 'land_marks');
        if (!empty($request->activity_ids)) {
            LandMarkActivity::where('land_mark_id', $landMark->id)->delete();
            foreach ($request->activity_ids as $activityId) {
                LandMarkActivity::create([
                    'land_mark_id' => $landMark->id,
                    'activity_id' => $activityId
                ]);
            }
        }
        foreach ($request->season_availability as $seasonAvailability) {
            LandMarkSeason::create([
                'landmark_id' => $landMark->id,
                'season_id' => $seasonAvailability
            ]);
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
        $data['header_title'] = 'Edit LandMark';
        $data['cities'] = City::where('status', 1)->get();
        $data['record'] = LandMark::find($id);
        $data['regions'] = Region::where('status', 1)->get();
        $data['seasonsAvailability'] = Season::where('status', 1)->get();
        $data['activityTypes'] = ActivityType::where('status', 1)->get();
        return view('admin.land_marks.edit', $data);
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
        $landMark = LandMark::find($id);
        $this->storeLandMark($landMark, $request);
        return redirect('admin/land-marks/list')->with('success', 'LandMark updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = LandMark::where('id', $request->data_id)->first();
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
            Excel::import(new LandMarkImport(), $request->file('file'));

            exit;
            gc_enable();
            set_time_limit($originalTimeLimit);

            return response()->json(['status' => 'success', 'message' => 'Data imported successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong. ' . $e->getMessage()], 200);
        }
    }
}
