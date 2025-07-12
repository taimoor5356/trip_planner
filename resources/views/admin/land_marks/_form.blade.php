<div class="mb-3">
    <label class="form-label" for="name">Land Mark Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Land Mark Name">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="city_id">Cities</label>
    <div class="input-group input-group-merge">
        <select name="city_id" id="city_id" class="form-control">
            <option value="" disabled selected>Select City</option>
            @if(!empty($cities))
            @foreach($cities as $city)
            <option value="{{$city->id}}" {{ isset($record) ? ($record->city_id == $city->id ? 'selected' : '') : '' }}>{{$city->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="location">Location</label>
    <div class="input-group input-group-merge">
        <input type="text" name="location" value="{{isset($record)?$record->location:''}}" class="form-control" placeholder="Enter Location">
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
<div class="mb-3">
    <label class="form-label" for="activity_ids">Activities</label>
    <div class="input-group input-group-merge">
        @php($selectedSeasons = isset($record->activity_ids) ? json_decode($record->activity_ids, true) : [])

        <select name="activity_ids[]" id="activity_ids" class="form-control select2" multiple>
            <option value="">Select Activity</option>
            @if(!empty($activityTypes))
                @foreach($activityTypes as $activityType)
                    <option value="{{ $activityType->id }}" 
                        {{ in_array($activityType->id, $selectedSeasons) ? 'selected' : '' }}>
                        {{ $activityType->name }}
                    </option>
                @endforeach
            @endif
        </select>
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