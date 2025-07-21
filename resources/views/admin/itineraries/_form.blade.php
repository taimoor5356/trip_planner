@section('_styles')
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<style>
    
    .select2-container .select2-selection--single {
        display: block;
        width: 100%;
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: black !important;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--multiple:focus {
        border: 1px solid #ced4da;
    }
</style>
@endsection
<div class="row">
    <!-- Start Headline -->
    <div class="col-md-6 mb-3">
        <label class="form-label text-dark" for="head_line">Headline *</label>
        <div class="input-group input-group-merge">
            <input type="text" name="head_line" id="head_line"
                class="form-control"
                value="{{ isset($record) ? $record->head_line : '' }}" placeholder="Enter Headline">
        </div>
    </div>

    <!-- Start Headline -->
    <div class="col-md-6 mb-3">
        <label class="form-label text-dark" for="tag_line">Tagline *</label>
        <div class="input-group input-group-merge">
            <input type="text" name="tag_line" id="tag_line"
                class="form-control"
                value="{{ isset($record) ? $record->tag_line : '' }}" placeholder="Enter Headline">
        </div>
    </div>

    <!-- Mode of Travel -->
    <div class="col-md-6 mb-3">
        <label class="form-label text-dark" for="mode_of_travel">Choose mode of travel *</label>
        <div class="input-group input-group-merge">
            <select name="mode_of_travel" id="mode_of_travel" class="form-control">
                <option value="" disabled selected>Mode of Travel</option>
                <option value="1" {{ (isset($record) && ($record->mode_of_travel == 1)) ? 'selected' : '' }}>By Road</option>
                <option value="2" {{ (isset($record) && ($record->mode_of_travel == 2)) ? 'selected' : '' }}>By Air</option>
            </select>
        </div>
    </div>

    <!-- Starting Point -->
    <div class="col-md-6 mb-3">
        <label class="form-label text-dark" for="starting_point">Choose starting point *</label>
        <div class="input-group input-group-merge">
            <select name="starting_point" id="starting_point" class="form-control">
                <option value="" disabled selected>Starting Point</option>
                @foreach(\App\Models\Origin::where('status',  1)->get() as $origin)
                    <option value="{{ strtolower($origin->id) }}" {{ (isset($record) && ($record->origin_id == $origin->id)) == strtolower($origin->id) ? 'selected' : '' }}>{{ $origin->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Destination -->
    <div class="col-md-6 mb-3">
        <label class="form-label text-dark" for="destination">Choose destination *</label>
        <div class="input-group input-group-merge">
            <select name="destination" id="destination" class="form-control">
                <option value="" disabled selected>Trip Destination</option>
                @if (!empty($regions) && isset($record))
                @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ $region->id == $record->destination_id ? 'selected' : '' }}>{{ $region->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-lg-6 form-group mb-2">
        <label for="image" class="form-label">Upload Image</label>
        <input type="file" accept="image/*" class="form-control" id="images"
            name="images[]" onchange="display_image(this)">
    
        <div class="col-lg-8 form-group preview-image-wrapper {{ (isset($record) && count($record->images) > 0) ? 'd-block' : 'd-none' }}">
            <label for="image_preview" class="form-label">Image Preview</label>
            <img id="image_preview"
                src="{{ (isset($record) && count($record->images)>0) ? asset('imgs/itineraries/' . $record->images->first()->image) : '#' }}"
                alt="Image Preview"
                class="img-thumbnail box-image-preview {{ (isset($record) && count($record->images) > 0) ? 'd-block' : 'd-none' }}" />
        </div>
        <small>{{ (isset($record) && count($record->images) > 0) ? $record->images->first()->image : '' }}</small>
    </div>
    
    <!-- Trip Duration -->
    <div class="col-md-6 mb-3">
        <label class="form-label text-dark" for="trip_duration">Trip duration *</label>
        <div class="input-group input-group-merge">
            <select name="trip_duration" id="trip_duration" class="form-control">
                <option value="" disabled selected>Number of Days</option>
                @if (isset($record))
                @foreach(\App\Models\OriginDestination::where('origin_id', $record->origin_id)->where('mode_of_travel', $record->mode_of_travel)->where('destination_id', $record->destination_id)->get() as $dur)
                    <option value="{{ $dur->days_nights }}" {{ $record->trip_duration == $dur->days_nights ? 'selected' : '' }}>{{ (ucfirst(str_replace('_', ' ', $dur->days_nights))) }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <!-- Status -->
    <div class="col-md-6 mb-3">
        <label class="form-label" for="status">Status</label>
        <div class="input-group input-group-merge">
            <select name="status" id="status" class="form-control">
                <option value="" disabled>Select Status</option>
                <option value="0">In Active</option>
                <option value="1" selected>Active</option>
            </select>
        </div>
    </div>
</div>

<!-- Dynamic Day Plan -->
<div id="day-wise-plan-section" class="@if(isset($record) && count($record->itineraryDays) > 0) @else d-none @endif mt-4">
    <label class="form-label fw-bold">Day-wise Plan</label>
    <div id="day-wise-fields">
        @if (isset($record) && count($record->itineraryDays) > 0)
        @for ($i = 0; $i < count($record->itineraryDays); $i++)
            @php 
                $itineraryDayWiseData = $record->itineraryDays[$i];
            @endphp
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Day {{$i+1}}</h5>
                    <div class="mb-2">
                        <label>Origin</label>
                        <input type="text" name="origins[{{$i}}][origin]" class="form-control" value="{{ $itineraryDayWiseData->origin }}" placeholder="Enter origin">
                    </div>
                    <div class="mb-2">
                        <label>Destination</label>
                        <select name="city_ids[{{$i}}][city_id]" class="form-control city-select" data-day="{{$i}}">
                            <option value="">Select Destination</option>
                            @foreach(\App\Models\City::where('status', 1)->get() as $city)
                                <option value="{{ $city->id }}" {{ $itineraryDayWiseData->destination_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Landmarks</label>
                        <select name="days[{{$i}}][landmarks][]" id="landmarks-day-{{$i}}" class="form-control landmark-select select2" multiple>
                            @foreach(\App\Models\LandMark::get() as $landmrk)
                            <option value="{{ $landmrk->id }}" {{ in_array($landmrk->id, json_decode($itineraryDayWiseData->landmarks)) ? 'selected' : '' }}>{{ $landmrk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endfor
        @endif
    </div>
</div>
@section('_scripts')
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    
    function display_image(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input)
                    .closest(".row")
                    .find(".preview-image-wrapper")
                    .addClass("d-block")
                    .removeClass("d-none");
                $(input)
                    .closest(".row")
                    .find("#image_preview")
                    .attr("src", e.target.result)
                    .addClass("d-block")
                    .removeClass("d-none");
                $(input)
                    .closest(".row")
                    .find("#remove_image")
                    .addClass("d-block")
                    .removeClass("d-none");
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true
        });
        const today = new Date().toISOString().split('T')[0];
        $('#trip_start_date').attr('min', today);

        let preselectedDestination = "{{ Request::get('destination') }}";
        let preselectedTripDuration = "{{ Request::get('trip_duration') }}";

        $('#mode_of_travel').on('change', function () {
            $('#starting_point').prop('selectedIndex', 0).trigger('change');
        });

        $('#starting_point').on('change', function () {
            const modeOfTravel = $('#mode_of_travel').val();
            const startingPoint = $('#starting_point').val();
            const _url = "{{ route('fetch_date_wise_destination') }}";

            $.post(_url, {
                _token: "{{ csrf_token() }}",
                itinerary_module: 'itinerary_module',
                mode_of_travel: modeOfTravel,
                starting_point: startingPoint
            }, function (response) {
                if (response.status) {
                    let _html = '<option value="" disabled selected>Trip Destination</option>';
                    response.availableByModeOfTravelAndRegions.forEach(d => {
                        const selected = (d.destination_id == preselectedDestination) ? 'selected' : '';
                        _html += `<option value="${d.destination_id}" ${selected}>${d.destination_region.name}</option>`;
                    });
                    $('#destination').html(_html);
                    if (preselectedDestination) $('#destination').trigger('change');
                }
            });
        });

        $('#destination').on('change', function () {
            const tripStartDate = $('#trip_start_date').val();
            const modeOfTravel = $('#mode_of_travel').val();
            const startingPoint = $('#starting_point').val();
            const _url = "{{ route('fetch_destination_wise_days') }}";

            $.post(_url, {
                _token: "{{ csrf_token() }}",
                mode_of_travel: modeOfTravel,
                starting_point: startingPoint,
                destination: $(this).val()
            }, function (response) {
                if (response.status) {
                    let _html = '<option value="" disabled selected>Number of Days</option>';
                    response.regionSeasonDays.forEach(d => {
                        const value = d.days_nights?.toLowerCase().replace(/\s+/g, '_') ?? '';
                        const selected = (value == preselectedTripDuration) ? 'selected' : '';
                        if (value) {
                            _html += `<option value="${value}" ${selected}>${d.days_nights.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase())}</option>`;
                        }
                    });
                    $('#trip_duration').html(_html);
                    $('#trip_duration').trigger('change');
                }
            });
        });

        $('#trip_duration').on('change', function () {
            const val = $(this).val();
            const numberOfDays = parseInt(val.split('_')[0]) || 0;
            const container = $('#day-wise-fields');
            container.html('');
            if (numberOfDays > 0) {
                $('#day-wise-plan-section').removeClass('d-none');
                for (let i = 1; i <= numberOfDays; i++) {
                    container.append(`
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="mb-3">Day ${i}</h5>
                                <div class="mb-2">
                                    <label>Origin</label>
                                    <input type="text" name="origins[${i}][origin]" class="form-control" placeholder="Enter origin">
                                </div>
                                <div class="mb-2">
                                    <label>Destination</label>
                                    <select name="city_ids[${i}][city_id]" class="form-control city-select" data-day="${i}">
                                        <option value="">Select Destination</option>
                                        @foreach(\App\Models\City::where('status', 1)->get() as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Landmarks</label>
                                    <select name="days[${i}][landmarks][]" id="landmarks-day-${i}" class="form-control landmark-select select2" multiple>
                                        @foreach(\App\Models\LandMark::get() as $landmrk)
                                        <option value="{{ $landmrk->id }}">{{ $landmrk->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    `);

                    // initialize select2 for each landmark field after appending
                    $('#landmarks-day-' + i).select2({
                        placeholder: 'Select Landmarks',
                        allowClear: true
                    });
                }
            } else {
                $('#day-wise-plan-section').addClass('d-none');
            }
        });

        // $(document).on('change', '.city-select', function () {
        //     const cityId = $(this).val();
        //     const day = $(this).data('day'); // from data-day attribute
        //     const $landmarkSelect = $(`#landmarks-day-${day}`);

        //     // Clear previous options
        //     // $landmarkSelect.html('');

        //     if (!cityId) return;

        //     // Example using AJAX to get landmarks by city ID
        //     $.ajax({
        //         url: "{{ route('fetch_city_landmarks') }}",
        //         // url: "{{ route('fetch_landmarks') }}",
        //         method: "POST",
        //         data: {
        //             _token: '{{ csrf_token() }}',
        //             city_id: cityId
        //         },
        //         success: function(response) {
        //             if (response.status == true) {
        //                 let options = '';
        //                 response.landMarks.forEach(l => {
        //                     options += `<option value="${l.id}">${l.name}</option>`;
        //                 });

        //                 // $landmarkSelect.html(options);

        //                 // Re-initialize select2
        //                 $landmarkSelect.select2({
        //                     placeholder: 'Select Landmarks',
        //                     allowClear: true
        //                 });
        //             }
        //         }
        //     });
        // });
        
    });
</script>
@endsection