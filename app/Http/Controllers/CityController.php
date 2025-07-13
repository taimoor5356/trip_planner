<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Imports\CityImport;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class CityController extends Controller
{
    public $headerTitle = 'City';
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
            ->addColumn('region_name', function ($row) {
                return ucwords($row->region?->name);
            })
            ->addColumn('city_name', function ($row) {
                return ucwords($row->name);
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
                        $btns .= '<a class="btn btns m-0 p-1" data-city-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-city" data-city-id="'.$row->id.'" href="#">
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
            $records = City::orderBy('id', 'asc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = 'Cities List';
        return view('admin.cities.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = 'Add New';
        $data['regions'] = Region::where('status', 1)->get();
        return view('admin.cities.create', $data);
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
        $city = new City();
        $this->storeCity($city, $request);
        return redirect('admin/cities/list')->with('success', 'City added successfully');
    }

    public function storeCity($city, $request) {
        $city->name = $request->name;
        $city->status = $request->status ?? 0;
        $city->region_id = $request->region_id ?? 0;
        $city->save();
        Helpers::storeImage($request, \App\Models\CityImage::class, $city, 'city_id', 'cities');
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
        $data['header_title'] = 'Edit City';
        $data['record'] = City::find($id);
        $data['regions'] = Region::where('status', 1)->get();
        return view('admin.cities.edit', $data);
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
        $city = City::find($id);
        $this->storeCity($city, $request);
        return redirect('admin/cities/list')->with('success', 'City updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = City::where('id', $request->data_id)->first();
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
            Excel::import(new CityImport(), $request->file('file'));

            exit;
            gc_enable();
            set_time_limit($originalTimeLimit);

            return response()->json(['status' => 'success', 'message' => 'Data imported successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong. ' . $e->getMessage()], 200);
        }
    }
}
