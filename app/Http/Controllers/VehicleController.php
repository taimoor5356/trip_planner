<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Imports\VehicleImport;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleCity;
use App\Models\VehicleType;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class VehicleController extends Controller
{
    public $headerTitle = 'Vehicle';
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
            ->addColumn('registration_number', function ($row) {
                return ucwords($row->registration_number);
            })
            ->addColumn('capacity_adults', function ($row) {
                return ucwords($row->capacity_adults);
            })
            ->addColumn('capacity_children', function ($row) {
                return ucwords($row->capacity_children);
            })
            ->addColumn('infants', function ($row) {
                return ucwords($row->infants);
            })
            ->addColumn('brand', function ($row) {
                return ucwords($row->brand);
            })
            ->addColumn('model', function ($row) {
                return ucwords($row->model);
            })
            ->addColumn('region_name', function ($row) {
                return ucwords($row->city?->name);
            })
            ->addColumn('per_day_cost', function ($row) {
                return ucwords($row->per_day_cost);
            })
            ->addColumn('vehicle_type_name', function ($row) {
                return ucwords($row->vehicle_type?->name);
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
                        $btns .= '<a class="btn btns m-0 p-1" data-vehicle-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-vehicle" data-vehicle-id="'.$row->id.'" href="#">
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
            $records = Vehicle::orderBy('id', 'asc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = 'Vehicles List';
        return view('admin.vehicles.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = 'Add New';
        // $data['regions'] = Region::where('status', 1)->get();
        $data['cities'] = City::where('status', 1)->get();
        $data['vehicleTypes'] = VehicleType::where('status', 1)->get();
        return view('admin.vehicles.create', $data);
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
        $vehicle = new Vehicle();
        $this->storeVehicle($vehicle, $request);
        return redirect('admin/vehicles/list')->with('success', 'Vehicle added successfully');
    }

    public function storeVehicle($vehicle, $request) {
        $vehicle->name = $request->name;
        $vehicle->registration_number = $request->registration_number ?? null;
        $vehicle->capacity_adults = $request->capacity_adults ?? null;
        $vehicle->capacity_children = $request->capacity_children ?? 0;
        $vehicle->infants = $request->infants ?? 0;
        $vehicle->brand = $request->brand ?? null;
        $vehicle->model = $request->model ?? null;
        // $vehicle->region_id = $request->region_id ?? null;
        $vehicle->city_id = json_encode($request->city_id) ?? null;
        $vehicle->per_day_cost = $request->per_day_cost ?? null;
        $vehicle->vehicle_type_id = $request->vehicle_type_id ?? null;
        $vehicle->status = $request->status ?? null;
        $vehicle->save();
        VehicleCity::where('vehicle_id', $vehicle->id)->delete();
        foreach($request->city_id as $cityId) {
            VehicleCity::create([
                'vehicle_id' => $vehicle->id,
                'city_id' => $cityId
            ]);
        }
        // Helpers::storeImage($request, \App\Models\VehicleImage::class, $vehicle, 'vehicle_id', 'vehicles');
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
        $data['header_title'] = 'Edit Vehicle';
        $data['record'] = Vehicle::find($id);
        // $data['regions'] = Region::where('status', 1)->get();
        $data['cities'] = City::where('status', 1)->get();
        $data['vehicleTypes'] = VehicleType::where('status', 1)->get();
        return view('admin.vehicles.edit', $data);
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
        $vehicle = Vehicle::find($id);
        $this->storeVehicle($vehicle, $request);
        return redirect('admin/vehicles/list')->with('success', 'Vehicle updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = Vehicle::where('id', $request->data_id)->first();
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
            Excel::import(new VehicleImport(), $request->file('file'));

            exit;
            gc_enable();
            set_time_limit($originalTimeLimit);

            return response()->json(['status' => 'success', 'message' => 'Data imported successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong. ' . $e->getMessage()], 200);
        }
    }
}
