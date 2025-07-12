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
    <label class="form-label" for="name">Town Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Town Name">
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