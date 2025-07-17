<div class="mb-3">
    <label class="form-label" for="province_id">Provinces</label>
    <div class="input-group input-group-merge">
        <select name="province_id" id="province_id" class="form-control">
            <option value="" disabled selected>Select Province</option>
            @if(!empty($provinces))
            @foreach($provinces as $province)
            <option value="{{$province->id}}" {{ isset($record) ? ($record->province_id == $province->id ? 'selected' : '') : '' }}>{{$province->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="name">Region Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Region Name">
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