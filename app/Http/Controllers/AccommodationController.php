<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use DataTables;
use App\Models\Town;
use App\Models\Built;
use App\Models\Category;
use App\Models\BuildingType;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\PropertyAmenity;
use App\Models\RoomCategoryCost;
use App\Imports\AccomodationImport;
use App\Models\AccommodationImage;
use Maatwebsite\Excel\Facades\Excel;

class AccommodationController extends Controller
{
    public $headerTitle = 'Accomodation';
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
            ->addColumn('building_type_id', function ($row) {
                return ucwords($row->building?->name);
            })
            ->addColumn('built_id', function ($row) {
                return ucwords($row->built_names_list);
            })
            ->addColumn('default_status', function ($row) {
                return ucwords($row->default_status == "yes" ? 'Yes' : 'No');
            })
            ->addColumn('status', function ($row) {
                return ucwords($row->status == 1 ? 'Active' : 'In Active');
            })
            ->addColumn('category_id', function ($row) {
                return ucwords($row->category_names_list);
            })
            ->addColumn('property_amenities_id', function ($row) {
                return ucwords($row->amenity_names_list);
            })
            ->addColumn('location', function ($row) {
                return ucwords($row->location);
            })
            ->addColumn('town_id', function ($row) {
                return ucwords($row->town?->name);
            })
            ->addColumn('num_of_rooms', function ($row) {
                return ucwords($row->num_of_rooms);
            })
            ->addColumn('front_desk_contact', function ($row) {
                return ucwords($row->front_desk_contact);
            })
            ->addColumn('sales_contact', function ($row) {
                return ucwords($row->sales_contact);
            })
            ->addColumn('fb_link', function ($row) {
                return ucwords($row->fb_link);
            })
            ->addColumn('insta_link', function ($row) {
                return ucwords($row->insta_link);
            })
            ->addColumn('website_link', function ($row) {
                return ucwords($row->website_link);
            })
            ->addColumn('created_at', function ($row) {
                return ucwords($row->created_at);
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-accommodation-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-accommodation" data-accommodation-id="'.$row->id.'" href="#">
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
            $records = Accommodation::orderBy('id', 'asc');
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = $this->headerTitle;
        return view('admin.accommodations.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = $this->headerTitle;
        $data['buildingTypes'] = BuildingType::where('status', 1)->get();
        $data['builtTypes'] = Built::where('status', 1)->get();
        $data['roomCategories'] = RoomCategory::where('status', 1)->get();
        $data['categories'] = Category::where('status', 1)->get();
        $data['propertyAmenities'] = PropertyAmenity::where('status', 1)->get();
        $data['towns'] = Town::where('status', 1)->get();
        return view('admin.accommodations.create', $data);
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
        $accommodation = new Accommodation();
        $this->storeAccommodation($accommodation, $request);
        return redirect('admin/accommodations/list')->with('success', 'Accommodation added successfully');
    }

    public function storeAccommodation($accommodation, $request) {
        try {
            $accommodation->name = $request->name;
            $accommodation->building_type_id = $request->building_type_id ?? null;
            $accommodation->built_id = json_encode($request->built_id) ?? null;
            $accommodation->default_status = $request->default_status ?? 'no';
            $accommodation->status = $request->status ?? 1;
            $accommodation->category_id = json_encode($request->category_id) ?? null;
            $accommodation->property_amenities_id = json_encode($request->property_amenities_id) ?? null;
            $accommodation->location = $request->location ?? null;
            $accommodation->town_id = $request->town_id ?? null;
            $accommodation->num_of_rooms = $request->num_of_rooms ?? null;
            $accommodation->front_desk_contact = $request->front_desk_contact ?? null;
            $accommodation->sales_contact = $request->sales_contact ?? null;
            $accommodation->fb_link = $request->fb_link ?? null;
            $accommodation->insta_link = $request->insta_link ?? null;
            $accommodation->website_link = $request->website_link ?? null;
            $accommodation->save();
            RoomCategoryCost::where('accommodation_id', $accommodation->id)->delete();

            if ($request->has('room_category_amounts')) {
                foreach ($request->room_category_amounts as $roomCategoryId => $amounts) {
                    foreach ($amounts as $amount) {
                        if (!empty($amount)) {
                            RoomCategoryCost::create([
                                'accommodation_id'    => $accommodation->id,
                                'room_category_id'    => $roomCategoryId,
                                'price'               => $amount,
                                'is_default'          => $roomCategoryId == $request->room_category_default_selected ? 1 : 0,
                            ]);
                        }
                    }
                }
            }
            Helpers::storeImage($request, \App\Models\AccommodationImage::class, $accommodation, 'accommodation_id', 'accommodations');
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
        $data['header_title'] = $this->headerTitle;
        $data['record'] = Accommodation::with('roomCategories')->find($id);
        $data['buildingTypes'] = BuildingType::where('status', 1)->get();
        $data['builtTypes'] = Built::where('status', 1)->get();
        $data['roomCategories'] = RoomCategory::where('status', 1)->get();
        // ✅ fetch prices for each room category
        $data['roomCategoryAmounts'] = RoomCategoryCost::where('accommodation_id', $id)
            ->pluck('price', 'room_category_id')
            ->toArray();

        // ✅ fetch which one is default
        $data['roomCategoryDefault'] = RoomCategoryCost::where('accommodation_id', $id)
            ->where('is_default', 1)
            ->value('room_category_id');
        $data['categories'] = Category::where('status', 1)->get();
        $data['propertyAmenities'] = PropertyAmenity::where('status', 1)->get();
        $data['towns'] = Town::where('status', 1)->get();
        return view('admin.accommodations.edit', $data);
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
        $accommodation = Accommodation::find($id);
        $this->storeAccommodation($accommodation, $request);
        return redirect('admin/accommodations/list')->with('success', 'Accommodation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = Accommodation::where('id', $request->data_id)->first();
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
