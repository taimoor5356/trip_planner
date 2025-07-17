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
<!-- <div class="mb-3">
    <label class="form-label" for="mode_of_travel">Mode of Travel</label>
    <div class="input-group input-group-merge">
        <select name="mode_of_travel" id="mode_of_travel" class="form-control">
            <option value="" disabled selected>Select Mode of Travel</option>
            <option value="1" {{ isset($record) ? ($record->mode_of_travel == 1 ? 'selected' : '') : '' }}>By Road</option>
            <option value="2" {{ isset($record) ? ($record->mode_of_travel == 2 ? 'selected' : '') : '' }}>By Air</option>
        </select>
    </div>
</div> -->
<div class="mb-3">
    <label class="form-label" for="no_of_days">No of Days</label>
    <div class="input-group input-group-merge">
        <input type="text" name="no_of_days" id="no_of_days" value="@isset($record){{ $record->no_of_days }}@endisset" class="form-control" placeholder="Enter No of Days">
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