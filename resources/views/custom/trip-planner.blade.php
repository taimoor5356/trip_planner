@extends('layout.custom-app')
@section('_styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@php 
    $img = '';
    $imgData = \App\Models\RegionImage::where('region_id', Request::get('destination'))->first();
    if (isset($imgData)) {
        $img = $imgData->image;
    }
@endphp
<style>
    .butns {
        width: auto !important;
        display: flex !important;
        align-items: baseline;
    }
    .img-card {
        background-image: url("{{ asset('imgs/regions/'.$img) }}");
        height: 300px;
        background-position: fixed;
        background-size: cover;
    }
    
    .heading-name {
        position: absolute;
        bottom: 20px;
    }
    .heading-buttons {
        position: absolute;
        top: 20px;
        right: 20px;
    }
    .data-card {
        position: relative;
        color: white;
        height: 200px; /* Adjust as needed */
        padding: 2rem;
        overflow: hidden;
        border-radius: 10px;
    }

    .header-items {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 1rem 2rem;
    }

    .header-buttons {
        position: absolute;
        top: 1rem;
        right: 2rem;
    }

    .header-text {
        position: absolute;
        bottom: 1rem;
        left: 2rem;
    }

    .header-text h1, .header-text h5 {
        margin: 0;
    }
    /* .daily-plan-header-image {
        background-image: url('https://in.musafir.com/uploads/5_4_d115f28046.webp');
        height: 250px;
        background-position: fixed;
        background-size: cover;
    } */
    .carousel-control-prev-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        height: 20px;
        width: 20px;
    }
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        height: 20px;
        width: 20px;
    }
</style>
@endsection
@section('content')

    @php
        $origin = \App\Models\Origin::where('id', Request::get('starting_point'))->first();
        $modeOfTravel = Request::get('mode_of_travel') == 1 ? 'By Road' : 'By Air';
        $destination = \App\Models\Region::with('image')->where('id', Request::get('destination'))->first();
        $category = \App\Models\Category::where('id', Request::get('trip_budget'))->first();
        $vehicle = \App\Models\Vehicle::where('id', Request::get('vehicle_id'))->first();
        $cost = \App\Models\RoomCategoryCost::where('id', Request::get('trip_budget'))->first();
        $allVehicles = \App\Models\Vehicle::where('status', 1)->whereRaw('(capacity_adults + capacity_children) >= ?', [Request::get('adults') + Request::get('kids')])->get();
        $queryParams = [
            'trip_start_date' => Request::get('trip_start_date'),
            'mode_of_travel' => Request::get('mode_of_travel'),
            'starting_point' => Request::get('starting_point'),
            'destination' => Request::get('destination'),
            'trip_duration' => Request::get('trip_duration'),
            'trip_budget' => Request::get('trip_budget'),
            'adults' => Request::get('adults'),
            'kids' => Request::get('kids'),
            'ages_all_kids' => request()->get('ages_all_kids'),
            'infants' => request()->get('infants'),
            'number_of_rooms_required' => Request::get('number_of_rooms_required'),
            'vehicle_id' => Request::get('vehicle_id'),
        ];
    @endphp

    <div class="container flex-grow-1 container-p-y d-flex justify-content-center mt-4">
        @php
        $fetchItinerary = \App\Models\Itinerary::with('images')->where('mode_of_travel', $queryParams['mode_of_travel'])->where('origin_id', $queryParams['starting_point'])->where('destination_id', Request::get('destination'))->where('trip_duration', $queryParams['trip_duration'])->first();
        $itineraryDailyPlans = [];
        if (isset($fetchItinerary)) {
            $itineraryDailyPlans = \App\Models\ItineraryDayWisePlan::with('destination.image')->where('itinerary_id', $fetchItinerary->id)->get();
        }
        @endphp
        @if (isset($fetchItinerary))
        <div class="card col-lg-7">
            <input type="hidden" name="itinerary_id" value="{{ $fetchItinerary->id }}">
            <div class="card-header p-0" style="margin-bottom: 30px; overflow: hidden; border-radius: 5px 5px 0 0;">
                <div class="position-relative">
                    <!-- Full-width image -->
                    @php 
                        $itineraryImage = $fetchItinerary->images->first();
                    @endphp
                    <img src="{{ asset('imgs/itineraries/'.(isset($itineraryImage) ? $itineraryImage->image : '').'') }}"
                        alt=""
                        class="img-fluid w-100"
                        style="height: 200px; object-fit: cover;">

                    <!-- Overlay content -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-3">
                        <!-- Top-right buttons -->
                        <div class="d-flex justify-content-end">
                            <a href="{{ url('/') . '?' . http_build_query($queryParams) }}" class="btn btn-light btn-sm me-2" style="opacity: 0.8;">
                                <i class="bx bx-pencil"></i> Edit
                            </a>
                            <button class="btn btn-light btn-sm" id="share-link-btn" data-bs-toggle="modal" data-bs-target="#share-link-modal" style="opacity: 0.8;">
                                <i class="bx bx-share"></i> Share
                            </button>
                        </div>

                        <!-- Bottom-left text -->
                        <div>
                            <h1 class="text-white">
                                @isset($destination){{ $destination->name }} @endisset Adventure
                            </h1>
                            <h5 class="text-white">{{ $fetchItinerary->tag_line }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body" style="padding: 15px 15px">
                                <h6><i class="bx bx-map mb-1 text-primary"></i> Origin</h6>
                                <h6 class="text-capitalize fw-bold p-0 m-0">
                                    {{ $origin->name }}
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body" style="padding: 15px 15px">
                                <h6><i class="bx bx-trip mb-1 text-primary"></i> Mode of Travel</h6>
                                <h6 class="text-capitalize fw-bold p-0 m-0">
                                    {{ $modeOfTravel }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body" style="padding: 15px 15px">
                                <h6><i class="bx bx-calendar mb-1 text-primary"></i> Start Date & Duration</h6>
                                <h6 class="text-capitalize fw-bold p-0 m-0">
                                    <span class="text-capitalize fw-bold p-0 m-0">{{ \Carbon\Carbon::parse(Request::get('trip_start_date'))->format('d M, Y') }} ({{ ucwords(str_replace('_', ' ',Request::get('trip_duration'))) }}</span>)
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body" style="padding: 15px 15px">
                                <h6><i class="bx bx-user mb-1 text-primary"></i> Travelers</h6>
                                <h6 class="text-capitalize fw-bold p-0 m-0">
                                    {{ (Request::get('adults')) }} adults, {{ (Request::get('kids')) }} kids, {{ (Request::get('infants')) }} infants
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body" style="padding: 15px 15px">
                                <h6><i class="bx bx-bed mb-1 text-primary"></i> Accomodation Preference</h6>
                                <h6 class="text-capitalize fw-bold p-0 m-0">
                                    @isset($category){{ $category->name }}@endisset
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body" style="padding: 15px 15px">
                                <h6><i class="bx bx-car mb-1 text-primary"></i> Vehicle</h6>
                                <select id="new_vehicle_id" style="padding: 0px 15px; border-radius: 5px; margin-top: -20px; border: 1px solid lightgray; width: 100%">
                                    @foreach($allVehicles as $vhicle)
                                        <option value="{{$vhicle->id}}" data-vehicle-charges="{{ $vhicle->per_day_cost }}" {{ $vhicle->id == Request::get('vehicle_id') ? 'selected' : '' }}>{{$vhicle->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <div class="price-total d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h3 class="text-white border border-white rounded-circle py-2 px-1">
                                                RS
                                            </h3>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="text-white">Trip total for {{ Request::get('adults') + Request::get('kids') }} persons</h6>
                                            <h3 class="text-white">
                                                <span class="text-capitalize fw-bold total-trip-price" total-trip-planner-cost="{{ (($vehicle->per_day_cost) * (preg_match('/\d+/', Request::get('trip_duration'), $matches) ? $matches[0] : '') ?? 0) + ($category->price) }}">
                                                Loading...    
                                                <!-- PKR {{ (($vehicle->per_day_cost) * (preg_match('/\d+/', Request::get('trip_duration'), $matches) ? $matches[0] : '') ?? 0) + ($category->price) }} -->
                                                </span>
                                            </h3>
                                            <input type="hidden" 
                                                name="total_category_price" 
                                                value="{{ (($vehicle->per_day_cost) * (preg_match('/\d+/', Request::get('trip_duration'), $matches) ? $matches[0] : '') ?? 0) + ($category->price) }}" 
                                                class="total_category_price">
                                            <input type="hidden" value="" class="changeable-total-trip-planner-cost">
                                            <input type="hidden" value="{{ preg_match('/\d+/', Request::get('trip_duration'), $matches) ? $matches[0] : 0 }}" class="trip-duration">
                                            <input type="hidden" value="{{ $vehicle->per_day_cost }}" class="existing-vehicle-charges">
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-info btn-sm book-a-trip">
                                            Book This Trip
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div>
                            <h3 class="text-primary fw-bold">Daily Plan</h3>
                        </div>
                        <div class="card p-0" style="box-shadow: none !important;">
                            <div class="card-body p-0">
                                <div class="mb-3">
                                    @foreach($itineraryDailyPlans as $key1 => $itinerary)
                                    <div class="accordion accordion-flush mb-3 p-0" id="dailyPlanFlush-{{$key1}}">
                                        <div class="accordion-item p-0" style="box-shadow: none !important;">
                                            <h2 class="accordion-header p-0">
                                                <button class="accordion-button p-0 collapsed fw-bolder text-dark bg-light" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#daily-plan-flush-collapse-{{$key1}}"
                                                    aria-expanded="false" aria-controls="daily-plan-flush-collapse-{{$key1}}">
                                                        <h6 class="fw-bold text-dark">
                                                            Day {{$key1+1}}: {{$itinerary->origin}} <small class="text-secondary fw-normal"> @if($key1 == 0) {{\Carbon\Carbon::parse(Request::get('trip_start_date'))->format('D, M d')}} @else {{\Carbon\Carbon::parse(Request::get('trip_start_date'))->addDays($key1)->format('D, M d')}} @endif</small>
                                                        </h6>
                                                </button>
                                            </h2>
                                            <div id="daily-plan-flush-collapse-{{$key1}}" class="accordion-collapse collapse"
                                                data-bs-parent="#dailyPlanFlush-{{$key1}}">
                                                <div class="accordion-body p-0">
                                                    {{--<!-- <div class="col-12 mb-4 p-0">
                                                        <div class="daily-plan-header-image rounded" style="background-image: url('{{ asset('imgs/regions/'.$itinerary->destination?->image?->image) }}'); height: 250px; background-position: fixed; background-size: cover;">
                                                        </div>
                                                    </div> -->--}}
                                                    <div class="col-12 mb-4">
                                                        <h6 class="text-dark fw-bold"><i class="bx bx-building text-primary mb-1"></i> Places Covered:</h6>
                                                        <div class="row">
                                                            @foreach(json_decode($itinerary->landmarks) as $landMarkId)
                                                                @php 
                                                                    $landMark = \App\Models\LandMark::with('activities.activity', 'image')->where('id', $landMarkId)->first();
                                                                @endphp
                                                                <div class="col-md-4 mb-3">
                                                                    <div class="card h-100" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#land-mark-activities-modal{{ $itinerary->id }}{{ $landMark->id }}" 
                                                                        style="cursor:pointer; height: 200px;"> {{-- Adjust card height as needed --}}
                                                                        
                                                                        <img src="{{ asset('imgs/land_marks/' . $landMark->image?->image) }}"
                                                                            class="img-fluid"
                                                                            alt=""
                                                                            style="height: 100px; width: 100%; object-fit: cover; border-radius: 5px 5px 0px 0px;">
                                                                            
                                                                        <small class="card-footer p-1 text-start fw-bold text-dark" 
                                                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">
                                                                            {{ $landMark->name ?? '' }}
                                                                            @php
                                                                                $activityNames = $landMark->activities->pluck('activity.name')->filter()->implode(', ');
                                                                            @endphp
                                                                            <br>
                                                                            <small class="text-start fw-light">Activities:</small>
                                                                            <br>
                                                                            <small class="text-start fw-light">{{ \Illuminate\Support\Str::limit($activityNames, 60, '...') }}</small>
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                
                                                                <div class="modal fade modal-center" id="land-mark-activities-modal{{$itinerary->id}}{{$landMark->id}}" tabindex="-1" role="dialog" aria-labelledby="land-mark-activities-modal{{$itinerary->id}}{{$landMark->id}}" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content modal-center">
                                                                            <div class="modal-header">
                                                                                Landmark Activities
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    @foreach($landMark->activities as $lmActivity)
                                                                                        <div class="col-md-4">
                                                                                            <div class="card p-3">
                                                                                                {{ ucfirst($lmActivity->activity?->name) }}
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-4">
                                                        <h6 class="text-dark fw-bold"><i class="bx bx-music mb-1"></i> Activities:</h6>
                                                        <ul style="padding-left: 15px;">
                                                            @isset($landMark)
                                                                @php
                                                                    $activityIds = $landMark->activity_ids;

                                                                    // Convert to array if it's valid JSON and not empty/null
                                                                    $decodedIds = [];

                                                                    if (!empty($activityIds) && $activityIds !== 'null') {
                                                                        $decoded = json_decode($activityIds, true);
                                                                        if (is_array($decoded)) {
                                                                            $decodedIds = $decoded;
                                                                        }
                                                                    }
                                                                @endphp

                                                                @if (!empty($decodedIds))
                                                                    @foreach ($decodedIds as $landMarkActivity)
                                                                        @php 
                                                                            $activity = \App\Models\ActivityType::find($landMarkActivity);
                                                                        @endphp
                                                                        <li>{{ $activity?->name }}</li>
                                                                    @endforeach
                                                                @endif
                                                            @endisset
                                                        </ul>
                                                    </div>
                                                    <div class="col-12 mb-4">
                                                        <div class="card" style="box-shadow: none;">
                                                            <div class="card-body p-0" style="box-shadow: none;">
                                                                <h6 class="text-dark fw-bold"><i class="bx bx-buildings mb-1"></i> Accommodations:</h6>

                                                                @php
                                                                    $regionIds = \App\Models\Region::where('status', 1)->where('id', Request::get('destination'))->pluck('id')->toArray();
                                                                    $cityIds = \App\Models\City::where('status', 1)->whereIn('region_id', $regionIds)->pluck('id')->toArray();
                                                                    $townIds = \App\Models\Town::where('status', 1)->whereIn('city_id', $cityIds)->pluck('id')->toArray();
                                                                    $accommodations = \App\Models\Accommodation::with('roomCategories.roomCategory', 'images')
                                                                        ->where('city_id', $itinerary->destination_id)
                                                                        ->get();
                                                                    $accommod = \App\Models\Accommodation::with('roomCategories.roomCategory', 'images')
                                                                        ->where('city_id', $itinerary->destination_id)
                                                                        ->where('default_status', 1)
                                                                        ->first();
                                                                @endphp

@if($accommodations->count() > 0)
@if ($accommod)
    <div class="row accommodation-data-list{{$key1}} accommodation-datalist">
        <div>
            <small class="text-dark text-decoration-underline fw-bold">{{ $accommod->name }}</small>
            <div>
                @php
                    $defaultRoom = $accommod->roomCategories->firstWhere('is_default', 1);
                @endphp
                <small class="text-dark">
                    <span class="fw-bold">Price:</span>
                    @if ($defaultRoom)
                        PKR <span class="dynamic-price">{{ $defaultRoom->price }}</span>/night
                    @else
                        Not available
                    @endif
                </small>
            </div>
        </div>
        <div class="col-lg-7">
            <div id="accommodationCarousel{{$key1}}" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($accommod->images as $index => $accommodation)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="border-radius: 5px;">
                            <div class="col-md-12 mb-3" style="border-radius: 5px;">
                                <div class="card h-100">
                                    <img src="{{ asset('imgs/accommodations/'.$accommodation->image) }}" class="img-fluid" alt="" style="border-radius: 5px; height: 250px; width: 100%; object-fit: cover">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-5 single-accommodation-data text-dark" data-index="{{ $key1 }}">
            <small>
                <span class="fw-bold text-decoration-underline">Room Categories:</span> <br>
                @if ($accommod->roomCategories && count($accommod->roomCategories) > 0)
                    <div class="row">
                    @foreach($accommod->roomCategories as $key3 => $roomCategory)
                        <div class="col-12 d-flex align-items-center">
                            <div>
                                <input type="radio" 
                                {{ $roomCategory->is_default == 1 ? 'checked' : '' }}
                                name="room-category-radio-{{ $key1 }}"
                                class="room-category-radio mt-1"
                                data-room-category-price="{{ $roomCategory->price }}"
                                data-accommodation-id="{{ $key1 }}">
                            </div>
                            <div class="ms-1">{{ $roomCategory->roomCategory?->name }}</div>
                            {!! !$loop->last ? '<br>' : '' !!}
                        </div>
                    @endforeach
                    </div>
                @else
                    No categories found
                @endif
            </small>
            <br>
            <button class="btn btn-primary btn-sm" data-bs-target="#change-hotel-modal{{$key1}}" data-bs-toggle="modal">Change Hotel</button>
        </div>
        <div class="modal fade modal-center all-accommodations-modal" id="change-hotel-modal{{$key1}}" tabindex="-1" role="dialog" aria-labelledby="change-hotel-modal{{$key1}}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-center">
                    <div class="modal-header">
                        All Accommodations
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach($accommodations as $accomoKey => $accommo)
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100">
                                        <div id="accommodationCarousel{{$key1}}" class="carousel-class">
                                            <div class="carousel-inner">
                                                @foreach($accommo->images as $index => $singleAccommodation)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="border-radius: 5px;">
                                                        <div class="col-md-12 mb-3" style="border-radius: 5px;">
                                                            <div class="card h-100">
                                                                <img src="{{ asset('imgs/accommodations/'.$singleAccommodation->image) }}" class="img-fluid" alt="" style="border-radius: 5px 5px 0px 0px; height: 100px; width: 100%; object-fit: cover">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="card-footer py-0 px-1">
                                            <small class="text-center text-dark">
                                                {{ $accommo->name }}
                                            </small>
                                            <hr>
@php
    $roomCategories = $accommo->roomCategories->map(function($cat) {
        return [
            'price' => $cat->price,
            'is_default' => $cat->is_default,
            'room_category' => ['name' => $cat->roomCategory->name ?? ''],
        ];
    });
@endphp

<a href="#"
    class="mb-1 btn btn-primary btn-sm select-accommodation"
    data-accommodation='@json([
        'name' => $accommo->name,
        'room_categories' => $roomCategories
    ])'
    data-index="{{ $key1 }}"
> Select </a>
                                            <a href="#" class="show-accommodation-details mb-1 btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#view-accommodation-details-{{$key1}}{{$accomoKey}}">View</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade modal-center accommodation-details-modal" id="view-accommodation-details-{{$key1}}{{$accomoKey}}" tabindex="-1" role="dialog" aria-labelledby="view-accommodation-details-{{$key1}}{{$accomoKey}}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content modal-center">
                                            <div class="modal-header">
                                                Accommodation Details
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card h-100">
                                                            
                                                            <div class="card-footer py-0 px-1">
                                                                <small class="text-center text-dark">
                                                                    
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <!-- <button class="carousel-control-prev" type="button" data-bs-target="#accommodationCarousel{{$key1}}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#accommodationCarousel{{$key1}}" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button> -->
    </div>
@endif
@else
    <p>No accommodations found.</p>
@endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-12 mb-4">
                                                        <h6 class="text-dark fw-bold">Meals:</h6>
                                                        <p>
                                                            Breakfast included, Lunch & Dinner
                                                        </p>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 border-bottom border-secondary">
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body border border-light rounded">
                                <h5 class="fw-bold">
                                    <i class="bx bx-check-circle mb-1 text-success"></i> What's included
                                </h5>
                                <small>
                                    <i class="bx bx-check-circle mb-1 text-success"></i> Nights of stay as per plan with breakfast
                                </small>
                                <br>
                                <small>
                                    <i class="bx bx-check-circle mb-1 text-success"></i> Days of vehicle with driver (if selected)
                                </small>
                                <br>
                                <small>
                                    <i class="bx bx-check-circle mb-1 text-success"></i> Fuel, toll taxes, Parking fee (for included vehicle)
                                </small>
                                <br>
                                <small>
                                    <i class="bx bx-check-circle mb-1 text-success"></i> Jeep routes mentioned in plan
                                </small>
                                <br>
                                <small>
                                    <i class="bx bx-check-circle mb-1 text-success"></i> Driver's allowance (for included vehicle)
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card">
                            <div class="card-body border border-light rounded">
                                <h5 class="fw-bold">
                                    <i class="bx bx-x-circle mb-1 text-danger"></i> What's Excluded
                                </h5>
                                <small>
                                    <i class="bx bx-x-circle mb-1 text-danger"></i> Meals other than breakfast
                                </small>
                                <br>
                                <small>
                                    <i class="bx bx-x-circle mb-1 text-danger"></i> Entry tickets to attractions
                                </small>
                                <br>
                                <small>
                                    <i class="bx bx-x-circle mb-1 text-danger"></i> Anything not listed in the included section
                                </small>
                                <br>
                                <small>
                                    <i class="bx bx-x-circle mb-1 text-danger"></i> Personal expenses and optional activities
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4 d-flex align-items-center">
                    <div class="col-md-4 text-center mb-4">
                        <button class="btn btn-secondary btn-sm">Plan another trip</button>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <h6 class="text-primary"><small>Trip total for {{ Request::get('adults') + Request::get('kids') }} persons</small></h6>
                        <h3 class="text-primary">
                            <span class="text-capitalize fw-bold text-primary total-trip-price">
                                <small class="loader-price-done">
                                    Loading...
                                    <!-- PKR {{ (($vehicle->per_day_cost) * (preg_match('/\d+/', Request::get('trip_duration'), $matches) ? $matches[0] : '') ?? 0) + ($category->price) }} -->
                                </small>
                            </span>
                        </h3>
                        <button class="btn btn-info btn-sm book-a-trip">
                            Book This Trip
                        </button>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <small>Trip ID: {{$fetchItinerary->trip_id}}</small>
                    </div>
                    <input type="hidden" name="total_final_amount" class="total-final-amount" value="0">
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="modal fade modal-center" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content modal-center">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <!-- Logo -->
                            <div class="text-center">
                            <i class="bx bx-user bg-primary text-white p-2 rounded-circle fs-2"></i>
                            </div>
                            <div class="app-brand text-center justify-content-center mb-0 mt-3">
                            <a href="#" class="app-brand-link">
                                <span class="app-brand-text text-body fw-bolder" style="font-size:xx-large">Sign In</span>
                            </a>
                            </div>
                            <p class="text-center mt-2">Access your Trip Planner Pro account.</p>
                            <!-- /Logo -->
                            <p class="mb-4">
                            </p>
                            <form class="mb-3" id="login-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="sign_in_method" class="form-label">{{ __('Login with') }}</label>
                                    <select name="sign_in_method" id="sign_in_method" class="form-control bg-light">
                                    <option value="email_address"><i class="bx bx-user"></i> Email Address</option>
                                    <option value="mobile_number"><i class="bx bx-user"></i> Mobile Number</option>
                                    </select>
                                </div>
                                <div class="mobile-number-card d-none">
                                    <div class="mb-3">
                                    <label for="mobile_number" class="form-label">{{ __('Mobile Number') }}</label>
                                    <input
                                        type="number"
                                        class="form-control bg-light"
                                        id="mobile_number"
                                        name="mobile_number"
                                        placeholder="Enter your mobile number"
                                        value="{{old('mobile_number')}}" />
                                    </div>
                                </div>
                                <div class="email-address-card">
                                    <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input
                                        type="email"
                                        class="form-control bg-light"
                                        id="email"
                                        name="email"
                                        placeholder="Enter your email"
                                        required
                                        value="{{old('email')}}" />
                                    </div>
                                </div>
                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">{{ __('Password') }}</label>
                                    <!-- <a href="auth-forgot-password-basic.html">
                                        <small>Forgot Password?</small>
                                        </a> -->
                                    </div>
                                    <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>

                                <input type="hidden" name="trip_login" id="trip_login" value="trip_login">
                                <input type="hidden" name="trip_signup" id="trip_signup" value="trip_signup">
                                <input type="hidden" name="book_itinerary_id" id="book_itinerary_id" value="@isset($fetchItinerary){{$fetchItinerary->id}}@endisset">
                                <input type="hidden" name="link" id="link" value="{{url()->full()}}">

                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" id="submit-trip-login">Sign in</button>
                                </div>
                                <div class="text-center">
                                Don't have account? 
                                <a class="mb-3 text-center" id="signup-button" href="#">
                                    Sign up
                                </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-center" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="signup-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content modal-center">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <!-- Logo -->
                            <div class="text-center">
                            <i class="bx bx-user bg-primary text-white p-2 rounded-circle fs-2"></i>
                            </div>
                            <div class="app-brand text-center justify-content-center mb-0 mt-3">
                            <a href="#" class="app-brand-link">
                                <span class="app-brand-text text-body fw-bolder" style="font-size:xx-large">Sign Up</span>
                            </a>
                            </div>
                            <p class="text-center mt-2">Access your Trip Planner Pro account.</p>
                            <!-- /Logo -->
                            <p class="mb-4">
                            </p>
                            <form class="mb-3">
                                @csrf
                                <div class="name-card">
                                    <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                    <input
                                        type="name"
                                        class="form-control bg-light"
                                        id="signup_name"
                                        name="name"
                                        placeholder="Enter your name"
                                        required
                                        value="{{old('name')}}" />
                                    </div>
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="sign_up_method" class="form-label">{{ __('Sign up via Mobile number or Email') }}</label>
                                    <select name="sign_up_method" id="sign_up_method" class="form-control bg-light">
                                    <option value="email_address">Email Address</option>
                                    <option value="mobile_number">Mobile Number</option>
                                    </select>
                                </div> -->
                                <div class="mobile-number-card">
                                    <div class="mb-3">
                                    <label for="mobile_number" class="form-label">{{ __('Mobile Number') }}</label>
                                    <input
                                        type="number"
                                        class="form-control bg-light"
                                        id="signup_mobile_number"
                                        name="mobile_number"
                                        placeholder="Enter your mobile number"
                                        value="{{old('mobile_number')}}" />
                                    </div>
                                </div>
                                <div class="email-address-card">
                                    <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input
                                        type="email"
                                        class="form-control bg-light"
                                        id="signup_email"
                                        name="email"
                                        placeholder="Enter your email"
                                        required
                                        value="{{old('email')}}" />
                                    </div>
                                </div>
                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">{{ __('Password') }}</label>
                                    <!-- <a href="auth-forgot-password-basic.html">
                                        <small>Forgot Password?</small>
                                        </a> -->
                                    </div>
                                    <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="signup_password"
                                        class="form-control bg-light"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                                <div class="mb-3 form-privacy-policy">
                                    <div class="d-flex justify-content-between">
                                    <label class="form-label" for="privacy-policy">{{ __('privacy-policy') }}</label>
                                    <!-- <a href="auth-forgot-privacy-policy-basic.html">
                                        <small>Forgot privacy-policy?</small>
                                        </a> -->
                                    </div>
                                    <div class="input-group input-group-merge">
                                    <span class="border border-primary form-control fs-6 d-flex justify-content-center align-items-center">
                                        <input type="checkbox" required name="privacy_policy" id=""> &nbsp; &nbsp;<small> I accept the Privacy Policy and Terms of Service.</small>
                                    </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" type="button" id="submit-trip-signup">Sign up</button>
                                </div>
                                <div class="mb-3 text-center sign-in-modal">
                                    Already have an account? <a href="#">Sign in</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-center" id="share-link-modal" tabindex="-1" role="dialog" aria-labelledby="share-link-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-center">
                <div class="modal-body">
                    Copy the link
                    <input type="text" readonly class="form-control" id="link-to-copy" value="{{ request()->fullUrl() }}">
                    <br>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="copy-the-link">Copy link</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('_scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        let queryParams = @json($queryParams);
        setTimeout(() => {
            calculateTotalRoomPrice();
        }, 500);
        
        $(document).on('click', '#copy-the-link', function () {
            const textToCopy = $('#link-to-copy').val();

            navigator.clipboard.writeText(textToCopy)
                .then(() => {
                    alert('Link copied to clipboard!');
                })
                .catch(err => {
                    console.error('Failed to copy: ', err);
                    alert('Could not copy the link.');
                });
        });

        $(document).on('change', '.room-category-radio', function () {
            let roomCategoryPrice = Number($(this).attr('data-room-category-price') || 0);
            $(this).closest('.accommodation-datalist').find('.dynamic-price').html(roomCategoryPrice);
            calculateTotalRoomPrice();
        });

        function calculateTotalRoomPrice() {
            let total = 0;

            // Get all checked radios from each accommodation
            $('.room-category-radio:checked').each(function () {
                total += Number($(this).data('room-category-price'));
            });
            
            let newVehicleCharges = Number($('#new_vehicle_id').find('option:selected').attr('data-vehicle-charges'));
            let tripDuration = $('.trip-duration').val();

            total += Number(newVehicleCharges) * Number(tripDuration);
            let percentPrice = Number(5/100) * Number(total);
            let totalOfAll = Number(total) + Number(percentPrice);
            // Do something with total
            $('.changeable-total-trip-planner-cost').val(Number(Number(totalOfAll)));
            $('.total-trip-price').html('PKR ' + Number(Number(totalOfAll)));
            $('.total-final-amount').val(Number(totalOfAll));
        }


        $(document).on('change', '#new_vehicle_id', function () {
            const vehicleId = $(this).val();
            const dataPerDayCost = $(this).find('option:selected').attr('data-vehicle-charges');

            const url = new URL(window.location.href);

            if (vehicleId) {
                url.searchParams.set('vehicle_id', vehicleId);
            } else {
                url.searchParams.delete('vehicle_id');
            }

            history.replaceState(null, '', url.toString());
            calculateTotalRoomPrice();
        });

        window.isGuest = {{ Auth::check() ? 'false' : 'true' }};
        $(document).on('click', '.book-a-trip', function() {
            if (window.isGuest) {
                $('#login-modal').modal('show');
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    title: 'Are you sure to Book the Trip?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'green',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, book it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{route('customer_book_trip')}}",
                            method: 'POST',
                            data: {
                                _token: "{{csrf_token()}}",
                                user_id: "@if(!is_null(Auth::user())){{Auth::user()->id}}@endif",
                                itinerary_id: $('#book_itinerary_id').val(),
                                link: $('#link').val(),
                                total_amount: $('.total-final-amount').val()
                            },
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire(
                                        response.message,
                                        response.msg,
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        response.message,
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            }
        });
        
        $(document).on('click', '.select-accommodation', function (e) {
            e.preventDefault();

            const accommodation = $(this).data('accommodation');
            const index = $(this).data('index');

            // Find container
            const $container = $(`.single-accommodation-data[data-index="${index}"]`);
            if ($container.length === 0) return;

            const $row = $container.closest('.row');

            // Update name
            const $nameEl = $row.find('small.text-decoration-underline');
            if ($nameEl.length) {
                $nameEl.text(accommodation.name);
            }
            // Update price (find default room category)
            const defaultRoom = accommodation.room_categories.find(cat => cat.is_default == 1);
            const $priceEl = $row.find('.dynamic-price');
            if (defaultRoom && $priceEl.length) {
                $priceEl.text(defaultRoom.price);
            }
            // Update room categories
            const $categoriesWrapper = $container.find('.row');
            if ($categoriesWrapper.length) {
                $categoriesWrapper.empty();
                accommodation.room_categories.forEach((cat, idx) => {
                    const isChecked = cat.is_default == '1' ? 'checked' : '';
                    const categoryHtml = `
                        <div class="col-12 d-flex align-items-center">
                            <div>
                                <input type="radio"
                                    name="room-category-radio-${index}"
                                    class="room-category-radio mt-1"
                                    data-room-category-price="${cat.price}"
                                    data-accommodation-id="${index}"
                                    ${isChecked}>
                            </div>
                            <div class="ms-1">${cat.room_category?.name ?? ''}</div>
                            ${idx !== accommodation.room_categories.length - 1 ? '<br>' : ''}
                        </div>
                    `;
                    $categoriesWrapper.append(categoryHtml);
                });
            }
            // Close modal
            const modalId = `#change-hotel-modal${index}`;
            const $modal = $(modalId);
            if ($modal.length) {
                const modalInstance = bootstrap.Modal.getInstance($modal[0]);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
            calculateTotalRoomPrice();
        });

        $(document).on('click', '#signup-button', function(e) {
            $('#login-modal').modal('hide');
            $('#signup-modal').modal('show');
        });

        $(document).on('click', '.sign-in-modal', function(e) {
            $('#signup-modal').modal('hide');
            $('#login-modal').modal('show');
        });

        $(document).on('click', '#submit-trip-login', function(e) {
            e.preventDefault();
            var tripLogin = $('#trip_login').val();

            var email = $('#email').val();
            var phone = $('#mobile_number').val();
            var password = $('#password').val();

            $.ajax({
                url: "{{route('post_login')}}",
                method: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    email: email, 
                    mobile_number: phone, 
                    password: password, 
                    trip_login: tripLogin
                },
                success: function(response) {
                    if (response.status == true) {
                        $('#login-modal').modal('hide');
                        Swal.fire(
                            'Logged in successfully!',
                            'success'
                        );
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        $('#login-modal').modal('hide');
                        Swal.fire(
                            'Failed!',
                            response.message,
                            'error'
                        );
                    }
                }
            });
        });

        $(document).on('click', '#submit-trip-signup', function(e) {
            e.preventDefault();
            var tripSignup = $('#trip_signup').val();

            var full_name = $('#signup_name').val();
            var email = $('#signup_email').val();
            var phone = $('#signup_mobile_number').val();
            var password = $('#signup_password').val();

            $.ajax({
                url: "{{route('post_register')}}",
                method: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    name: full_name, 
                    email: email, 
                    mobile_number: phone, 
                    password: password, 
                    trip_signup: tripSignup
                },
                success: function(response) {
                    if (response.status == true) {
                        $('#signup-modal').modal('hide');
                        Swal.fire(
                            'Account created successfully!',
                            'success'
                        );
                        if (response.resp == 'login_now') {
                            setTimeout(() => {
                                $('#login-modal').modal('show');
                            }, 200);
                        }
                    } else {
                        // $('#signup-modal').modal('hide');
                        // Swal.fire(
                        //     'Failed!',
                        //     response.message,
                        //     'error'
                        // );
                    }
                }
            });
        });

        $(document).on('click', '.show-accommodation-details', function() {
            $('.all-accommodations-modal').modal('hide');
            setTimeout(() => {
                $('.all-accommodations-modal').on('hidden.bs.modal', function () {
                    $('.accommodation-details-modal').modal('show');
                });
            }, 1000);
        });
    });

    // document.addEventListener('DOMContentLoaded', function () {
    //     // Handle click on "Select" button in modal
    //     document.querySelectorAll('.select-accommodation').forEach(button => {
    //         button.addEventListener('click', function (e) {
    //             e.preventDefault();

    //             const accommodation = JSON.parse(this.getAttribute('data-accommodation'));
    //             const index = this.getAttribute('data-index');

    //             // Update name
    //             const nameEl = document.querySelector(`.single-accommodation-data[data-index="${index}"]`).closest('.row').querySelector('small.text-decoration-underline');
    //             if (nameEl) nameEl.textContent = accommodation.name;

    //             // Update price (find default room category)
    //             const defaultRoom = accommodation.room_categories.find(cat => cat.is_default === 1);
    //             const priceEl = document.querySelector(`.single-accommodation-data[data-index="${index}"]`).closest('.row').querySelector('.dynamic-price');
    //             if (defaultRoom && priceEl) {
    //                 priceEl.textContent = defaultRoom.price;
    //             }

    //             // Update room categories
    //             const categoriesWrapper = document.querySelector(`.single-accommodation-data[data-index="${index}"] .row`);
    //             if (categoriesWrapper) {
    //                 categoriesWrapper.innerHTML = '';
    //                 accommodation.room_categories.forEach((cat, idx) => {
    //                     const categoryEl = document.createElement('div');
    //                     categoryEl.classList.add('col-12', 'd-flex', 'align-items-center');

    //                     categoryEl.innerHTML = `
    //                         <div>
    //                             <input type="radio" 
    //                                 name="room-category-radio-${index}"
    //                                 class="room-category-radio mt-1"
    //                                 data-room-category-price="${cat.price}"
    //                                 data-accommodation-id="${index}"
    //                                 ${cat.is_default === 1 ? 'checked' : ''}>
    //                         </div>
    //                         <div class="ms-1">${cat.room_category?.name ?? ''}</div>
    //                         ${idx !== accommodation.room_categories.length - 1 ? '<br>' : ''}
    //                     `;
    //                     categoriesWrapper.appendChild(categoryEl);
    //                 });
    //             }

    //             // Close modal
    //             const modal = document.getElementById(`change-hotel-modal${index}`);
    //             if (modal) {
    //                 const bsModal = bootstrap.Modal.getInstance(modal);
    //                 if (bsModal) bsModal.hide();
    //             }
    //         });
    //     });
    // });
    
</script>
@endsection