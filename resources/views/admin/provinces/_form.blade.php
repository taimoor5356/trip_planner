<div class="mb-3">
    <label class="form-label" for="country_id">Countries</label>
    <div class="input-group input-group-merge">
        <select name="country_id" id="country_id" class="form-control">
            <option value="" disabled selected>Select Country</option>
            @if(!empty($countries))
            @foreach($countries as $country)
            <option value="{{$country->id}}" {{ isset($record) ? ($record->country_id == $country->id ? 'selected' : '') : '' }}>{{$country->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="name">Province Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Province Name">
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