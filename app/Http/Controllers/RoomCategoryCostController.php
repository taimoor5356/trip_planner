<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\RoomAmenity;
use App\Models\RoomCategory;
use App\Models\RoomCategoryCost;
use Maatwebsite\Excel\Facades\Excel;

class RoomCategoryCostController extends Controller
{
    public $headerTitle = 'Room Categoy Cost';
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
            ->addColumn('accommodation_id', function ($row) {
                return ucwords($row->accommodation?->name);
            })
            ->addColumn('room_amenity_id', function ($row) {
                return ucwords($row->room_amenity_id);
            })
            ->addColumn('room_category_id', function ($row) {
                return ucwords($row->RoomCategory?->name);
            })
            ->addColumn('from', function ($row) {
                return ucwords($row->from);
            })
            ->addColumn('to', function ($row) {
                return ucwords($row->to);
            })
            ->addColumn('price', function ($row) {
                return ucwords($row->price);
            })
            ->addColumn('created_at', function ($row) {
                return ucwords($row->created_at);
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-room-category-cost-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-room-category-cost" data-room-category-cost-id="'.$row->id.'" href="#">
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
            $records = RoomCategoryCost::orderBy('id', 'desc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = 'Room Category Costs';
        return view('admin.room_category_costs.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = 'Add New';
        $data['accommodations'] = Accommodation::where('default_status', 1)->get();
        $data['roomAmenities'] = RoomAmenity::where('status', 1)->get();
        $data['roomCategories'] = RoomCategory::where('status', 1)->get();
        return view('admin.room_category_costs.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'accommodation_id' => 'required',
            'room_amenity_id' => 'required',
            'room_category_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'price' => 'required'
        ]);
        $roomCategoryCost = new RoomCategoryCost();
        $this->storeRoomCategoryCost($roomCategoryCost, $request);
        return redirect('admin/room-category-costs/list')->with('success', 'RoomCategoryCost added successfully');
    }

    public function storeRoomCategoryCost($roomCategoryCost, $request) {
        $roomCategoryCost->accommodation_id = $request->accommodation_id;
        $roomCategoryCost->room_amenity_id = $request->room_amenity_id ?? null;
        $roomCategoryCost->room_category_id = $request->room_category_id ?? null;
        $roomCategoryCost->from = $request->from ?? 0;
        $roomCategoryCost->to = $request->to ?? null;
        $roomCategoryCost->price = $request->price ?? null;
        $roomCategoryCost->save();
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
        $data['header_title'] = 'Edit Room Category Cost';
        $data['record'] = RoomCategoryCost::find($id);
        return view('admin.room_category_costs.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        request()->validate([
            'accommodation_id' => 'required',
            'room_amenity_id' => 'required',
            'room_category_id' => 'required',
            'from' => 'required',
            'to' => 'required',
            'price' => 'required'
        ]);
        $roomCategoryCost = RoomCategoryCost::find($id);
        $this->storeRoomCategoryCost($roomCategoryCost, $request);
        return redirect('admin/room-category-costs/list')->with('success', 'Room Category Cost updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = RoomCategoryCost::where('id', $request->data_id)->first();
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
