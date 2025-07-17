<div class="mb-3">
    <label class="form-label" for="name">Season Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Season Name">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="start_date">Season Start Date</label>
    <div class="input-group input-group-merge">
        <input type="date" name="start_date" value="{{isset($record)?$record->start_date:''}}" class="form-control">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="end_date">Season End Date</label>
    <div class="input-group input-group-merge">
        <input type="date" name="end_date" value="{{isset($record)?$record->end_date:''}}" class="form-control">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="region_id">Regions Covered</label>
    <div class="input-group input-group-merge">
        <select name="region_id[]" id="region_id" class="form-control select2" multiple>
            <!-- <option value="" disabled selected>Select Region</option> -->
            @if(!empty($regions))
            @foreach($regions as $region)
                <option value="{{ $region->id }}"
                    {{ isset($record) && in_array($region->id, $region->regionSeasons->pluck('region_id')->toArray() ?? []) ? 'selected' : '' }}>
                    {{ $region->name }}
                </option>
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