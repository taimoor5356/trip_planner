<?php

namespace App\Http\Controllers;

use App\Models\Origin;
use App\Models\OriginSeason;
use App\Models\Season;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class OriginSeasonController extends Controller
{
    public $headerTitle = 'Origin Season';
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
            ->addColumn('origin_name', function ($row) {
                return ucwords($row->origin?->name);
            })
            ->addColumn('season_name', function ($row) {
                return ucwords($row->season?->name);
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
                        $btns .= '<a class="btn btns m-0 p-1" data-origin-season-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-origin-season" data-origin-season-id="'.$row->id.'" href="#">
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
            $records = OriginSeason::orderBy('id', 'desc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = $this->headerTitle;
        return view('admin.origin_season.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = $this->headerTitle;
        $data['origins'] = Origin::where('status', 1)->get();
        $data['seasons'] = Season::where('status', 1)->get();
        return view('admin.origin_season.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'origin_id' => 'required|string'
        ]);
        OriginSeason::whereIn('season_id', $request->season_id)->where('origin_id', $request->origin_id)->delete();
        foreach ($request->season_id as $key => $season) {
            $originSeason = new OriginSeason();
            $originSeason->origin_id = $request->origin_id;
            $originSeason->season_id = $season;
            $originSeason->save();
        }
        return redirect('admin/origin-seasons/list')->with('success', 'Added successfully');
    }

    public function storeOriginSeason($originSeason, $request) {
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
        $data['record'] = OriginSeason::find($id);
        $data['origins'] = Origin::where('status', 1)->get();
        $data['seasons'] = Season::where('status', 1)->get();
        return view('admin.origin_season.edit', $data);
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
        $originSeason = OriginSeason::find($id);
        foreach ($request->season_id as $key => $season) {
            $originSeason->season_id = $season;
            $originSeason->save();
        }
        return redirect('admin/origin-seasons/list')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = OriginSeason::where('id', $request->data_id)->first();
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
