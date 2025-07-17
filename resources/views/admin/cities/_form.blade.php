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
    <label class="form-label" for="name">City Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter City Name">
    </div>
</div>
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