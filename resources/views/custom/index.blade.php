@extends('layout.custom-app')
@section('_styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .butns {
        width: auto !important;
        display: flex !important;
        align-items: baseline;
    }
    .adults-kids-button {
        width: 25px;
        height: 25px;
        padding: 0;
        font-size: 20px;
        line-height: 1;
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div class="container flex-grow-1 container-p-y d-flex justify-content-center">
    @include('_messages')
    <div class="card col-md-7 mt-4">
        <div class="card-header p-0" style="margin-bottom: 100px;">
            <div class="banner-image">
                <img src="https://gulmitcontinentalhotel.com/wp-content/uploads/2023/02/Sunrise-on-Rakaposhi-Mountain-Hunza-Valley.jpg" 
                    alt="" 
                    style="height: 140px; width: 100%; object-fit: cover; position: absolute; border-radius: 5px 5px 0px 0px;">
                <div class="img-fluid banner-text" style="position: relative; left: 35px; top: 35px">
                    <div class="d-flex">
                        <i class="menu-icon tf-icons bx bx-box mt-1 text-white"></i><h3 class="text-white">Design your trip here</h3>
                    </div>
                    <small class="text-white">Fill in the details below to customize your perfect trip.</small>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('design_my_trip') }}" method="GET">
                <div class="row">
                    <div class="col-12 rounded" style="background-color: rgba(255, 255, 255, 0.85);">

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label text-dark" for="trip_start_date"><i class="bx bx-calendar"></i> your trip start date *</label>
                                    <div class="input-group input-group-merge">
                                        <input type="date" name="trip_start_date" id="trip_start_date"
                                            class="form-control bg-light"
                                            value="{{ Request::get('trip_start_date') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label text-dark" for="mode_of_travel"><i class="bx bx-map"></i> Choose mode of travel *</label>
                                <div class="input-group input-group-merge">
                                    <select name="mode_of_travel" id="mode_of_travel" class="form-control bg-light">
                                        <option value="" disabled {{ Request::get('mode_of_travel') ? '' : 'selected' }}>Mode of Travel</option>
                                        <option value="1" {{ Request::get('mode_of_travel') == 1 ? 'selected' : '' }}>By Road</option>
                                        <option value="2" {{ Request::get('mode_of_travel') == 2 ? 'selected' : '' }}>By Air</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label text-dark" for="starting_point"><i class="bx bx-trip"></i> Choose starting point *</label>
                                <div class="input-group input-group-merge">
                                    <select name="starting_point" id="starting_point" class="form-control bg-light">
                                        <option value="" disabled {{ Request::get('starting_point') ? '' : 'selected' }}>Starting Point</option>
                                        @foreach(\App\Models\Origin::where('status',  1)->get() as $origin)
                                            <option value="{{strtolower($origin->id)}}" {{ Request::get('starting_point') == strtolower($origin->id) ? 'selected' : '' }}>{{$origin->name}}</option>
                                        @endforeach
                                        <!-- <option value="islamabad" {{ Request::get('starting_point') == 'islamabad' ? 'selected' : '' }}>Islamabad</option>
                                        <option value="lahore" {{ Request::get('starting_point') == 'lahore' ? 'selected' : '' }}>Lahore</option>
                                        <option value="skardu_airport" {{ Request::get('starting_point') == 'skardu_airport' ? 'selected' : '' }}>Skardu Airport</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label text-dark" for="destination"><i class="bx bx-map"></i> Choose destination *</label>
                                <div class="input-group input-group-merge">
                                    @php
                                    $seasons = \App\Models\Season::whereDate('start_date', '<=', \Carbon\Carbon::parse(Request::get('trip_start_date'))->format('Y-m-d'))
                                        ->whereDate('end_date', '>=', \Carbon\Carbon::parse(Request::get('trip_start_date'))->format('Y-m-d'))
                                        ->pluck('id')
                                        ->toArray();
                                    $regionSeasons = \App\Models\RegionSeason::selectRaw('MIN(id) as id, region_id')
                                        ->whereIn('season_id', $seasons)
                                        ->groupBy('region_id')
                                        ->pluck('region_id')
                                        ->toArray();
                                    $regions = \App\Models\OriginDestination::with('destinationRegion')
                                        ->where('origin_id', Request::get('starting_point'))
                                        ->where('mode_of_travel', Request::get('mode_of_travel'))
                                        ->whereIn('destination_id', $regionSeasons)
                                        ->select('destination_id', 'origin_id', 'mode_of_travel')
                                        ->distinct()
                                        ->get();
                                    $regionSeasonDays = \App\Models\OriginDestination::with('destinationRegion')->where('origin_id', Request::get('starting_point'))->where('mode_of_travel', Request::get('mode_of_travel'))->where('destination_id', Request::get('destination'))->get();
                                    @endphp
                                    <select name="destination" id="destination" class="form-control bg-light">
                                        <option value="" disabled selected>Trip Destination</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->destinationRegion->id }}" {{ $region->destinationRegion->id == Request::get('destination') ? 'selected' : '' }}>{{ $region->destinationRegion->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label text-dark" for="trip_duration"><i class="bx bx-calendar"></i> Trip duration *</label>
                                <div class="input-group input-group-merge">
                                    <select name="trip_duration" id="trip_duration" class="form-control bg-light">
                                        <option value="" disabled selected>Number of Days</option>
                                        @foreach($regionSeasonDays as $regionSeasonD)
                                            <option value="{{ str_replace(' ', '_', $regionSeasonD->days_nights) }}" {{ str_replace(' ', '_', $regionSeasonD->days_nights) == Request::get('trip_duration') ? 'selected' : '' }}>{{ $regionSeasonD->days_nights }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label text-dark" for="trip_budget"><i class="bx bx-bed"></i> Select trip budget *</label>
                                <div class="input-group input-group-merge">
                                    <select name="trip_budget" id="trip_budget" class="form-control bg-light">
                                        <option value="" disabled {{ Request::get('trip_budget') ? '' : 'selected' }}>Trip Type</option>
                                        @foreach(\App\Models\Category::where('status', 1)->get() as $category)
                                            <option value="{{ $category->id }}" {{ Request::get('trip_budget') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4><i class="bx bx-user mb-2"></i> Guests Information</h4>
                                        <div class="row">
                                            <!-- Adults -->
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label text-dark" for="adults"><i class="bx bx-user"></i> Adults *</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button" class="btn-sm btn btn-outline-secondary rounded-circle p-0 adults-kids-button"
                                                            onclick="changeValue('adults', -1)">−</button>

                                                    <input type="number" name="adults" id="adults"
                                                        class="form-control bg-light text-center"
                                                        style="width: 100%;"
                                                        value="{{ Request::get('adults', 0) }}" min="0">

                                                    <button type="button" class="btn-sm btn btn-outline-secondary rounded-circle p-0 adults-kids-button"
                                                            onclick="changeValue('adults', 1)">+</button>
                                                </div>
                                            </div>
                                            <!-- Kids -->
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label text-dark" for="kids"><i class="bx bx-face"></i> Kids *</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button" class="btn btn-outline-secondary rounded-circle p-0 adults-kids-button"
                                                            onclick="changeValue('kids', -1)">−</button>

                                                    <input type="number" name="kids" id="kids"
                                                        class="form-control bg-light text-center"
                                                        style="width: 100%;"
                                                        value="{{ Request::get('kids', 0) }}" min="0">

                                                    <button type="button" class="btn btn-outline-secondary rounded-circle p-0 adults-kids-button"
                                                            onclick="changeValue('kids', 1)">+</button>
                                                </div>
                                            </div>
                                            <!-- Infants -->
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label text-dark" for="infants"><i class="bx bx-happy"></i> Infants *</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button" class="btn btn-outline-secondary rounded-circle p-0 adults-kids-button"
                                                            onclick="changeValue('infants', -1)">−</button>

                                                    <input type="number" name="infants" id="infants"
                                                        class="form-control bg-light text-center"
                                                        style="width: 100%;"
                                                        value="{{ Request::get('infants', 0) }}" min="0">

                                                    <button type="button" class="btn btn-outline-secondary rounded-circle p-0 adults-kids-button"
                                                            onclick="changeValue('infants', 1)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <!-- Dynamic kids age input area -->
                            <div class="row kids-ages-row ((Request::get('kids') > 0 ? '' : 'd-none')) mb-3" id="kids-ages-container">
                                <div class="col-12">
                                    <div class="card">
                                        @if (Request::get('kids') > 0)
                                        <div class="card-body row bg-light" id="kids-age-fields">
                                            <h6>Kids Ages</h6>
                                            @for($i = 0; $i < Request::get('kids'); $i++)
                                                <!-- Filled by JS -->
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label text-dark" for="ages_all_kids_{{$i}}">Kid {{$i+1}} Age</label>
                                                    <input type="text" name="ages_all_kids[]" class="form-control bg-light"
                                                        placeholder="Enter Age" value="{{ Request::get('ages_all_kids')[$i] }}">
                                                </div>
                                            @endfor
                                        </div>
                                        @else
                                            <div class="card-body row bg-light" id="kids-age-fields">
                                                <!-- Filled by JS -->
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label text-dark" for="number_of_rooms_required"><i class="bx bx-bed"></i> No. of rooms required *</label>
                                <div class="input-group input-group-merge">
                                    <select name="number_of_rooms_required" id="number_of_rooms_required" class="form-control bg-light">
                                        <!-- Filled dynamically -->
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <label class="form-label text-dark" for="vehicle_id"><i class="bx bx-car"></i> Choose your vehicle *</label>
                                <div class="input-group input-group-merge">
                                    <select name="vehicle_id" id="vehicle_id" class="form-control bg-light">
                                        <option value="">Choose your vehicle</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="input-group">
                                <input type="submit" value="Plan My Trip" class="btn btn-primary form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('_scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    let preselectedDestination = "{{ Request::get('destination') }}";
    let preselectedTripDuration = "{{ Request::get('trip_duration') }}";
    let preselectedVehicle = "{{ Request::get('vehicle_id') }}";
    let preselectedRooms = "{{ Request::get('number_of_rooms_required') }}";

    $(document).ready(function() {

        const today = new Date().toISOString().split('T')[0];

        document.getElementById("trip_start_date").setAttribute("min", today);

        $('#starting_point').trigger('change');

        $(document).on('change', '#mode_of_travel', function() {
            $('#starting_point').prop('selectedIndex', 0);
            $('#starting_point').trigger('change');
        });

        $(document).on('change', '#starting_point', function() {
            var tripStartDate = $('#trip_start_date').val();
            var modeOfTravel = $('#mode_of_travel').val();
            var startingPoint = $('#starting_point').val();
            var _url = "{{ route('fetch_date_wise_destination') }}";

            $.post(_url, {
                _token: "{{ csrf_token() }}",
                trip_start_date: tripStartDate,
                mode_of_travel: modeOfTravel,
                starting_point: startingPoint
            }, function(response) {
                if (response.status) {
                    let _html = '<option value="" disabled selected>Trip Destination</option>';
                    response.availableByModeOfTravelAndRegions.forEach(d => {
                        let selected = (d.destination_id == preselectedDestination) ? 'selected' : '';
                        _html += `<option value="${d.destination_id}" ${selected}>${d.destination_region.name}</option>`;
                    });
                    $('#destination').html(_html);
                    if (preselectedDestination) $('#destination').trigger('change');
                }
            });
        });

        $(document).on('change', '#destination', function() {
            var _this = $(this);
            var _url = "{{ route('fetch_destination_wise_days') }}";
            var tripStartDate = $('#trip_start_date').val();
            var modeOfTravel = $('#mode_of_travel').val();
            var startingPoint = $('#starting_point').val();

            $.post(_url, {
                _token: "{{ csrf_token() }}",
                mode_of_travel: modeOfTravel,
                starting_point: startingPoint,
                destination: _this.val()
            }, function(response) {
                if (response.status) {
                    let _html = '<option value="" disabled selected>Number of Days</option>';
                    response.regionSeasonDays.forEach(d => {
                        let selected = (d.id == preselectedTripDuration) ? 'selected' : '';
                        if (d.days_nights != null || d.days_nights != undefined) {
                            _html += `<option value="${d.days_nights.toLowerCase().replace(/\s+/g, '_')}" ${selected}>${d.days_nights ?? 0}</option>`;
                        }
                    });
                    $('#trip_duration').html(_html);
                }
            });
        });

        $(document).on('keyup change click', '#adults, #kids, #mode_of_travel, .adults-kids-button', function () {
            let adults = parseInt($('#adults').val()) || 0;
            let kids = parseInt($('#kids').val()) || 0;
            let totalPeople = adults + kids;
            let minRooms = Math.ceil(totalPeople / 4);
            if (minRooms === 0) minRooms = 1;

            let $select = $('#number_of_rooms_required');
            $select.empty();
            for (let i = minRooms; i <= 6; i++) {
                let selected = (preselectedRooms == i) ? 'selected' : '';
                $select.append(`<option value="${i}" ${selected}>${i}</option>`);
            }

            let travelMode = $('#mode_of_travel').val();
            let regionId = $('#destination').val();

            $.post("{{ route('fetch_people_wise_vehicles') }}", {
                _token: "{{ csrf_token() }}",
                total_people: totalPeople,
                region_id: regionId,
                mode_of_travel: travelMode
            }, function (response) {
                if (response.status) {
                    let _html = '<option value="" disabled selected>Choose your vehicle</option>';

                    // If mode is "by road", show "Your own Car" first
                    if (travelMode === 'by_road') {
                        _html += `<option value="own_car">Your own Car</option>`;
                    }

                    response.vehicles.forEach(d => {
                        let selected = (d.id == preselectedVehicle) ? 'selected' : '';
                        _html += `<option value="${d.id}" ${selected}>${d.name}</option>`;
                    });

                    $('#vehicle_id').html(_html);
                }
            });
        });

        if ($('#adults').val() || $('#kids').val()) {
            $('#adults').trigger('change');
        }
        
        window.changeValue = function(id, step) {
            const input = document.getElementById(id);
            let value = parseInt(input.value) || 0;
            value += step;
            if (value < 0) value = 0;
            input.value = value;

            if (id === 'kids') {
                updateKidsAgeFields(value);
            }
        }

        function updateKidsAgeFields(kidCount) {
            const container = document.getElementById('kids-ages-container');
            const fieldsWrapper = document.getElementById('kids-age-fields');

            if (kidCount > 0) {
                container.classList.remove('d-none');
            } else {
                container.classList.add('d-none');
            }

            fieldsWrapper.innerHTML = '<h6>Kids Ages</h6>';

            for (let i = 1; i <= kidCount; i++) {
                const fieldHTML = `
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-dark" for="ages_all_kids_${i}">Kid ${i} Age</label>
                        <input type="text" name="ages_all_kids[]" id="ages_all_kids_${i}" class="form-control bg-light"
                            placeholder="Enter Age">
                    </div>`;
                fieldsWrapper.insertAdjacentHTML('beforeend', fieldHTML);
            }
        }

        // Call on page load to initialize fields if kids > 0
        document.addEventListener('DOMContentLoaded', function () {
            const kidsInput = document.getElementById('kids');
            if (kidsInput) {
                updateKidsAgeFields(parseInt(kidsInput.value) || 0);
            }
        });
        
    });
</script>
@endsection
