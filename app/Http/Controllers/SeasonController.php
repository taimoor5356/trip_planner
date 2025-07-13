<?php

namespace App\Http\Controllers;

use App\Imports\SeasonImport;
use App\Models\Region;
use App\Models\RegionSeason;
use App\Models\Season;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class SeasonController extends Controller
{
    public $headerTitle = 'Season';
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
            ->addColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('status', function ($row) {
                return ucwords($row->status);
            })
            ->addColumn('start_date', function ($row) {
                return ucwords($row->start_date);
            })
            ->addColumn('end_date', function ($row) {
                return ucwords($row->end_date);
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
                        $btns .= '<a class="btn btns m-0 p-1" data-season-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-season" data-season-id="'.$row->id.'" href="#">
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
            $records = Season::orderBy('id', 'asc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = 'Seasons List';
        return view('admin.seasons.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = 'Add New';
        $data['regions'] = Region::where('status', 1)->get();
        return view('admin.seasons.create', $data);
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
        $season = new Season();
        $this->storeSeason($season, $request);
        return redirect('admin/seasons/list')->with('success', 'Season added successfully');
    }

    public function storeSeason($season, $request) {
        $season->name = $request->name;
        $season->start_date = $request->start_date ?? null;
        $season->end_date = $request->end_date ?? null;
        $season->status = $request->status ?? 0;
        $season->save();
        if (!empty($request->region_id)) {
            RegionSeason::where('season_id', $season->id)->delete();
            foreach ($request->region_id as $region) {
                RegionSeason::create([
                    'season_id' => $season->id,
                    'region_id' => $region
                ]);
            }
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
        $data['header_title'] = 'Edit Season';
        $data['record'] = Season::find($id);
        $data['regions'] = Region::where('status', 1)->get();
        return view('admin.seasons.edit', $data);
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
        $season = Season::find($id);
        $this->storeSeason($season, $request);
        return redirect('admin/seasons/list')->with('success', 'Season updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = Season::where('id', $request->data_id)->first();
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
            Excel::import(new SeasonImport(), $request->file('file'));

            exit;
            gc_enable();
            set_time_limit($originalTimeLimit);

            return response()->json(['status' => 'success', 'message' => 'Data imported successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong. ' . $e->getMessage()], 200);
        }
    }
}
