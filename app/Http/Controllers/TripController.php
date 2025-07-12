<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\OriginDestination;
use App\Models\RegionSeason;
use App\Models\Season;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class TripController extends Controller
{
    public $headerTitle = 'Trip';
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
            ->addColumn('user', function ($row) {
                return ucwords($row->user?->name);
            })
            ->addColumn('itinerary', function ($row) {
                return ucwords($row->itinerary?->head_line);
            })
            ->addColumn('created_at', function ($row) {
                return ucwords($row->created_at);
            })
            ->addColumn('link', function ($row) {
                return '<a href="'.strtolower($row->link).'" class="btn btn-primary">View Trip</a>';
            })
            ->addColumn('actions', function ($row) use ($trashed) {
                $btns = '
                    <div class="actionb-btns-menu d-flex justify-content-center">';
                    if ($trashed == null) {
                        $btns .= '<a class="btn btns m-0 p-1" data-trip-id="'.$row->id.'" href="edit/'.$row->id.'">
                                <i class="align-middle text-primary" data-feather="edit">
                                </i>
                            </a>
                            <a class="btn btns m-0 p-1 delete-trip" data-trip-id="'.$row->id.'" href="#">
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
            ->rawColumns(['sr_no', 'checkbox', 'name', 'role', 'link', 'actions'])
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
            if (Auth::user()->hasRole('admin')) {
                $records = Trip::orderBy('id', 'asc');
            } else if (Auth::user()->hasRole('customer')) {
                $records = Trip::where('user_id', Auth::user()->id)->orderBy('id', 'asc');
            }
            return $this->datatables($request, $records);
        }
        $data['url_segment_two'] = request()->segment(2);
        $data['header_title'] = $this->headerTitle;
        if (Auth::user()->hasRole('customer')) {
            $data['records'] = Trip::with('itinerary.images')->where('user_id', Auth::user()->id)->orderBy('id', 'asc')->get();
            return view('customer.trips.index', $data);
        }
        return view('admin.trips.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data['header_title'] = $this->headerTitle;
        return view('admin.trips.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $trip = Itinerary::find($request->itinerary_id);
        if (Auth::user() && isset($trip)) {
            $user = User::where('user_type', 2)->where('id', Auth::user()->id)->first();
            if (isset($user)) {
                $this->storeTrip($trip, $user);
            }
        }
        return redirect('admin/trips/list')->with('success', 'Trip added successfully');
    }

    public function storeTrip($trip, $user) {
        Trip::create([
            'user_id' => $user->id,
            'itinerary_id' => $trip->id
        ]);
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
        $data['header_title'] = 'Edit Trip';
        $data['record'] = Trip::find($id);
        return view('admin.trips.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // request()->validate([
        //     'name' => 'required|string'
        // ]);
        // $trip = Trip::find($id);
        // $this->storeTrip($trip, $request);
        // return redirect('admin/trips/list')->with('success', 'Trip updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $accommodation = Trip::where('id', $request->data_id)->first();
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

    public function custom(Request $request, $customerLogout = null)
    {
        if (!empty($customerLogout) && $customerLogout == 'customer_logout')
        {
            Auth::logout();
        }
        $data['header_title'] = 'Custom';
        $data['seasons'] = Season::whereDate('start_date', '<=', Carbon::parse($request->trip_start_date)->format('Y-m-d'))
                ->whereDate('end_date', '>=', Carbon::parse($request->trip_start_date)->format('Y-m-d'))
                ->pluck('id')
                ->toArray();
        $data['regionSeasons'] = RegionSeason::selectRaw('MIN(id) as id, region_id')
                ->whereIn('season_id', $data['seasons'])
                ->groupBy('region_id')
                ->pluck('region_id')
                ->toArray();
        $data['availableByModeOfTravelAndRegions'] = OriginDestination::with('destinationRegion')
            ->where('origin_id', $request->starting_point)
            ->where('mode_of_travel', $request->mode_of_travel);
        if (!empty($request->itinerary_module)) {
            $data['itineraryRegionIds'] = Itinerary::where('origin_id', $request->starting_point)->where('mode_of_travel', $request->mode_of_travel)->pluck('destination_id')->toArray();
            $data['availableByModeOfTravelAndRegions'] = $data['availableByModeOfTravelAndRegions']->whereNotIn('destination_id', $data['itineraryRegionIds']);
        }
        if (empty($request->itinerary_module)) {
            $data['availableByModeOfTravelAndRegions'] = $data['availableByModeOfTravelAndRegions']->whereIn('destination_id', $data['regionSeasons']);
        }
        $data['availableByModeOfTravelAndRegions'] = $data['availableByModeOfTravelAndRegions']->select('destination_id', 'origin_id', 'mode_of_travel') // only required columns
            ->distinct()
            ->get();
        return view('custom.index', $data);
    }

    public function designMyTrip(Request $request)
    {
        try {
            $data['header_title'] = 'Trip Designed';
            return response(View::make('custom.trip-planner', $data)->render());
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Blade error: ' . $e->getMessage());

            // Redirect back or to a fallback page
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
