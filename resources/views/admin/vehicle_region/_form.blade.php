<div class="mb-3">
    <label class="form-label" for="vehicle_id">Vehicles</label>
    <div class="input-group input-group-merge">
        <select name="vehicle_id" id="vehicle_id" class="form-control">
            <option value="" disabled selected>Select Vehicle</option>
            @if(!empty($vehicles))
            @foreach($vehicles as $vehicle)
            <option value="{{$vehicle->id}}" {{ isset($record) ? ($record->vehicle_id == $vehicle->id ? 'selected' : '') : '' }}>{{$vehicle->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="region_id">Regions</label>
    <div class="input-group input-group-merge">
        <select name="region_id" id="region_id" class="form-control">
            <option value="" disabled selected>Select Region</option>
            @if(!empty($regions))
            @foreach($regions as $region)
            <option value="{{$region->id}}" {{ isset($record) ? ($record->region_id == $region->id ? 'selected' : '') : '' }}>{{$region->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="season_id">Seasons</label>
    <div class="input-group input-group-merge">
        <select name="season_id" id="season_id" class="form-control">
            <option value="" disabled selected>Select Season</option>
            @if(!empty($seasons))
            @foreach($seasons as $season)
            <option value="{{$season->id}}" {{ isset($record) ? ($record->season_id == $season->id ? 'selected' : '') : '' }}>{{$season->name}}</option>
            @endforeach
            @endif
        </select>
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