<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Season;
use App\Models\Vehicle;
use App\Models\VehicleRegion;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class VehicleRegionController extends Controller
{
    public $headerTitle = 'Vehicle Region';
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
            ->addColumn('vehicle', function ($row) {
                return ucwords($row->vehicle?->name);
            })
            ->addColumn('region', function ($row) {
                return ucwords($row->region?->name);
            })
            ->addColumn('season', function ($row) {
                return ucwords($row->season?->name);
            })
            // ->addColumn('actions', function ($row) use ($trashed) {
            //     $btns = '
            //         <div class="actionb-btns-menu d-flex justify-content-center">';
            //         if ($trashed == null) {
            //             $btns .= '<a class="btn btns m-0 p-1" data-vehicle-region-id="'.$row->id.'" href="edit/'.$row->id.'">
            //                     <i class="align-middle text-primary" data-feather="edit">
            //                     </i>
            //                 </a>
            //                 <a class="btn btns m-0 p-1 delete-vehicle-region" data-vehicle-region-id="'.$row->id.'" href="#">
            //                     <i class="align-middle text-danger" data-feather="trash-2">
            //                     </i>
            //                 </a>
            //             </div>';
            //         } else {
            //             $btns.= '<a class="btn btns m-0 p-1" href="restore/' . $row->id . '">
            //                     <i class="align-middle text-success" data-feather="refresh-cw">
            //                     </i>
            //                 </a>
            //             </div>';
            //         }
            //     return $btns;
            // })
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
            $records = VehicleRegion::with('vehicle', 'region', 'season')->orderBy('id', 'asc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = $this->headerTitle;
        return view('admin.vehicle_region.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = $this->headerTitle;
        $data['regions'] = Region::where('status', 1)->get();
        $data['seasons'] = Season::where('status', 1)->get();
        $data['vehicles'] = Vehicle::where('status', 1)->get();
        return view('admin.vehicle_region.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'region_id' => 'required',
            'season_id' => 'required',
            'vehicle_id' => 'required'
        ]);
        $regionSeason = new VehicleRegion();
        return $this->storeVehicleRegion($regionSeason, $request);
    }

    public function storeVehicleRegion($regionSeason, $request) {
        if (!VehicleRegion::where('region_id', $request->region_id)->where('season_id', $request->season_id)->where('vehicle_id', $request->vehicle_id)->exists()) {
            $regionSeason->region_id = $request->region_id;
            $regionSeason->season_id = $request->season_id ?? null;
            $regionSeason->vehicle_id = $request->vehicle_id ?? null;
            $regionSeason->save();
            return redirect('admin/vehicle-regions/list')->with('success', 'Successfully saved');
        }
        return redirect()->back()->with('error', 'Already exists');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleRegion $vehicleRegion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $data['header_title'] = 'Edit Region Season';
        $data['record'] = VehicleRegion::find($id);
        $data['regions'] = Region::where('status', 1)->get();
        $data['seasons'] = Season::where('status', 1)->get();
        $data['vehicles'] = Vehicle::where('status', 1)->get();
        return view('admin.vehicle_region.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        request()->validate([
            'region_id' => 'required',
            'season_id' => 'required',
            'vehicle_id' => 'required'
        ]);
        $regionSeason = VehicleRegion::find($id);
        return $this->storeVehicleRegion($regionSeason, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleRegion $vehicleRegion)
    {
        //
    }
}
