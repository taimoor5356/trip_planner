<div class="mb-3">
    <label class="form-label" for="name">{{$header_title}} Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Origin Name">
    </div>
</div>
<div class="row">
    <div class="col-12">
        <h5>Set Destinations</h5> 
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="by_road">By Road</label>
            <div class="input-group input-group-merge">
                <input type="checkbox" name="by_road" id="by_road" data-mode-of-travel="by_road" {{ !empty($byRoadDestinations) ? 'checked' : '' }}>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="by_road_destination_ids">Destinations</label>
            <div class="input-group input-group-merge">
                <!-- <select {{ !empty($byRoadDestinations) ? '' : 'disabled' }} name="by_road_destination_ids[]" id="by_road_destination_ids" class="form-control select2" multiple>
                    <option value="" disabled>Select Status</option>
                    @if (!empty($destinations))
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ in_array($destination->id, $byRoadDestinations ?? []) ? 'selected' : '' }}>{{ $destination->name }}</option>
                    @endforeach
                    @endif
                </select> -->
                <select name="by_road_destination_ids[]" id="by_road_destination_ids" class="form-control select2" multiple>
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}"
                            {{ in_array($destination->id, $byRoadDestinationIds ?? []) ? 'selected' : '' }}>
                            {{ $destination->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <div>
                <label class="form-label" for="by_air">By Air</label>
                <div class="input-group input-group-merge">
                    <input type="checkbox" name="by_air" id="by_air" data-mode-of-travel="by_air" {{ !empty($byAirDestinations) ? 'checked' : '' }}>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="by_air_destination_ids">Destinations</label>
            <div class="input-group input-group-merge">
                <!-- <select {{ !empty($byAirDestinations) ? '' : 'disabled' }} name="by_air_destination_ids[]" id="by_air_destination_ids" class="form-control select2" multiple>
                    <option value="" disabled>Select Status</option>
                    @if (!empty($destinations))
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ in_array($destination->id, $byAirDestinations ?? []) ? 'selected' : '' }}>{{ $destination->name }}</option>
                    @endforeach
                    @endif
                </select> -->
                
                <select name="by_air_destination_ids[]" id="by_air_destination_ids" class="form-control select2" multiple>
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id }}"
                            {{ in_array($destination->id, $byAirDestinationIds ?? []) ? 'selected' : '' }}>
                            {{ $destination->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6 by-road-destination-days d-none">
        <div class="mb-3">
            <label class="form-label" for="status">Destination Days</label>
            <div class="card">
                <div class="card-body">
                    <p>Skardu</p>
                    <div class="input-group input-group-merge mb-2">
                        <input type="text" class="form-control" placeholder="Enter no of days and nights"> <button class="btn btn-success">+</button>
                    </div>
                    <div class="input-group input-group-merge mb-2">
                        <input type="text" class="form-control" placeholder="Enter no of days and nights"> <button class="btn btn-danger">-</button>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
    <div class="col-md-6 by-air-destination-days d-none">
        <div class="mb-3">
            <label class="form-label" for="status">Destination Days</label>
            <div class="card">
                <div class="card-body">
                    <p>Skardu</p>
                    <div class="input-group input-group-merge mb-2">
                        <input type="text" class="form-control" placeholder="Enter no of days and nights"> <button class="btn btn-success">+</button>
                    </div>
                    <div class="input-group input-group-merge mb-2">
                        <input type="text" class="form-control" placeholder="Enter no of days and nights"> <button class="btn btn-danger">-</button>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>

<!-- Images -->
<div class="mb-3">
    <label class="form-label" for="images">Images</label>
    <div class="input-group input-group-merge">
        <input type="file" name="images[]" id="images" class="form-control" multiple>
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="status">Status</label>
    <div class="input-group input-group-merge">
        <select name="status" id="status" class="form-control">
            <option value="" disabled selected>Select Status</option>
            <option value="0" {{ isset($record) ? ($record->status == 0 ? 'selected' : '') : '' }}>In Active</option>
            <option value="1" {{ isset($record) ? ($record->status == 1 ? 'selected' : '') : '' }}>Active</option>
        </select>
    </div>
</div>

@section('_scripts')

<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function() {
        const byRoadExisting = @json($byRoadDestinations ?? []);
        const byAirExisting = @json($byAirDestinations ?? []);
        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true
        });
        
        $(document).on('change', '#by_road, #by_air', function() {
            if ($(this).attr('data-mode-of-travel') == 'by_road') {
                if ($(this).is(':checked')) {
                    $('#by_road_destination_ids').prop('disabled', false);
                } else {
                    $('#by_road_destination_ids').prop('disabled', true);
                }
            }
            if ($(this).attr('data-mode-of-travel') == 'by_air') {
                if ($(this).is(':checked')) {
                    $('#by_air_destination_ids').prop('disabled', false);
                } else {
                    $('#by_air_destination_ids').prop('disabled', true);
                }
            }
        });

        $('#by_road_destination_ids, #by_air_destination_ids').select2();

        const allDestinations = @json($destinations);

        function renderDestinations(mode) {
            let selectId = mode === 'road' ? '#by_road_destination_ids' : '#by_air_destination_ids';
            let containerClass = mode === 'road' ? '.by-road-destination-days' : '.by-air-destination-days';
            let inputPrefix = mode === 'road' ? 'destination_days_by_road' : 'destination_days_by_air';
            let existing = mode === 'road' ? byRoadExisting : byAirExisting;

            const selected = $(selectId).val();
            const container = $(containerClass);
            const cardBody = container.find('.card-body');
            cardBody.html('');

            if (selected && selected.length > 0) {
                container.removeClass('d-none');
                selected.forEach(id => {
                    const dest = allDestinations.find(d => d.id == id);
                    if (dest) {
                        let html = `<div class="destination-group mb-3" data-id="${dest.id}" data-prefix="${inputPrefix}">
                                        <label class="form-label">${dest.name}</label>
                                        <div class="destination-fields">`;

                        const daysArray = existing[id] || [];

                        if (daysArray.length === 0) {
                            // Render empty input
                            html += `<div class="input-group input-group-merge mb-2">
                                        <input type="text" class="form-control" name="${inputPrefix}[${dest.id}][]" placeholder="Enter no of days and nights">
                                        <button type="button" class="btn btn-success add-field">+</button>
                                    </div>`;
                        } else {
                            daysArray.forEach((value, index) => {
                                html += `<div class="input-group input-group-merge mb-2">
                                            <input type="text" class="form-control" name="${inputPrefix}[${dest.id}][]" placeholder="Enter no of days and nights" value="${value}">
                                            <button type="button" class="btn btn-success add-field">+</button>`;

                                // Add "-" only for 2nd and onward inputs
                                if (index > 0) {
                                    html += `<button type="button" class="btn btn-danger remove-field">-</button>`;
                                }

                                html += `</div>`;
                            });
                        }

                        html += `</div></div>`; // close fields + group
                        cardBody.append(html);
                    }
                });
            } else {
                container.addClass('d-none');
            }
        }

        // Bind change events
        $('#by_road_destination_ids').on('change', function () {
            renderDestinations('road');
        });

        $('#by_air_destination_ids').on('change', function () {
            renderDestinations('air');
        });

        // Trigger initial
        renderDestinations('road');
        renderDestinations('air');

        // Delegate "+" button
        $(document).on('click', '.add-field', function () {
            let group = $(this).closest('.destination-group');
            let id = group.data('id');
            let prefix = group.data('prefix');

            const newField = `
                <div class="input-group input-group-merge mb-2">
                    <input type="text" class="form-control" name="${prefix}[${id}][]" placeholder="Enter no of days and nights">
                    <button type="button" class="btn btn-success add-field">+</button>
                    <button type="button" class="btn btn-danger remove-field">-</button>
                </div>
            `;
            group.find('.destination-fields').append(newField);
        });

        // Delegate "-" button
        $(document).on('click', '.remove-field', function () {
            let group = $(this).closest('.destination-group');
            let fields = group.find('.input-group');
            if (fields.length > 1) {
                $(this).closest('.input-group').remove();
            }
        });
        
    });
</script>
@endsection