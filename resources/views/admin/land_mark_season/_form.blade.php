<div class="mb-3">
    <label class="form-label" for="landmark_id">Landmarks</label>
    <div class="input-group input-group-merge">
        <select name="landmark_id" id="landmark_id" class="form-control">
            <option value="" disabled selected>Select Landmark</option>
            @if(!empty($landMarks))
            @foreach($landMarks as $landMark)
            <option value="{{$landMark->id}}" {{ isset($record) ? ($record->landmark_id == $landMark->id ? 'selected' : '') : '' }}>{{$landMark->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="season_availability">Season Availability</label>
    <div class="input-group input-group-merge">
        @php($selectedSeasons = isset($record->season_availability) ? json_decode($record->season_availability, true) : [])
        <select name="season_availability[]" id="season_availability" class="form-control select2" multiple>
            <option value="">Select Season Availability</option>
            @if(!empty($seasonsAvailability))
                @foreach($seasonsAvailability as $seasonAvailability)
                    <option value="{{ $seasonAvailability->id }}" 
                        {{ in_array($seasonAvailability->id, $selectedSeasons) ? 'selected' : '' }}>
                        {{ $seasonAvailability->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>
{{--<div class="mb-3">
    <label class="form-label" for="season_type_id">Season types</label>
    <div class="input-group input-group-merge">
        <select name="season_type_id[]" id="season_type_id" class="form-control select2" multiple>
            <option value="">Select Season Type</option>
            @if(!empty($seasonTypes))
                @foreach($seasonTypes as $seasonType)
                    <option value="{{ $seasonType->id }}" 
                        {{ in_array($seasonType->id, $selectedSeasons) ? 'selected' : '' }}>
                        {{ $seasonType->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>--}}
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