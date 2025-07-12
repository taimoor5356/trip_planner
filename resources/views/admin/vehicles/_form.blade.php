<div class="mb-3">
    <label class="form-label" for="name">Vehicle Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Vehicle Name">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="registration_number">Registration Number</label>
    <div class="input-group input-group-merge">
        <input type="text" name="registration_number" value="{{isset($record)?$record->registration_number:''}}" class="form-control" placeholder="Enter Registration Number">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="capacity_adults">Capacity Adults</label>
    <div class="input-group input-group-merge">
        <input type="number" name="capacity_adults" value="{{isset($record)?$record->capacity_adults:''}}" class="form-control" placeholder="Enter Capacity Adults">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="capacity_children">Capacity Children</label>
    <div class="input-group input-group-merge">
        <input type="number" name="capacity_children" value="{{isset($record)?$record->capacity_children:''}}" class="form-control" placeholder="Enter Capacity Children">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="infants">Infants</label>
    <div class="input-group input-group-merge">
        <input type="number" name="infants" value="{{isset($record)?$record->infants:''}}" class="form-control" placeholder="Enter Infants">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="brand">Brand</label>
    <div class="input-group input-group-merge">
        <input type="text" name="brand" value="{{isset($record)?$record->brand:''}}" class="form-control" placeholder="Enter Brand">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="model">Model</label>
    <div class="input-group input-group-merge">
        <input type="text" name="model" value="{{isset($record)?$record->model:''}}" class="form-control" placeholder="Enter Model">
    </div>
</div>
<!-- <div class="mb-3">
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
</div> -->
<div class="mb-3">
    <label class="form-label" for="city_id">Cities</label>
    <div class="input-group input-group-merge">
        @php($selectedCities = isset($record->city_id) ? json_decode($record->city_id, true) : [])
        <select name="city_id[]" id="city_id" class="form-control select2" multiple>
            <option value="">Select City</option>
            @if(!empty($cities))
            @foreach($cities as $city)
            <option value="{{$city->id}}" {{ in_array($city->id, $selectedCities) ? 'selected' : '' }}>{{$city->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="per_day_cost">Per Day Cost</label>
    <div class="input-group input-group-merge">
        <input type="text" name="per_day_cost" value="{{isset($record)?$record->per_day_cost:''}}" class="form-control" placeholder="Enter Per Day Cost">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="vehicle_type_id">Vehicle Types</label>
    <div class="input-group input-group-merge">
        <select name="vehicle_type_id" id="vehicle_type_id" class="form-control">
            <option value="" disabled selected>Select Vehicle Type</option>
            @if(!empty($vehicleTypes))
            @foreach($vehicleTypes as $vehicle)
            <option value="{{$vehicle->id}}" {{ isset($record) ? ($record->vehicle_type_id == $vehicle->id ? 'selected' : '') : '' }}>{{$vehicle->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>

<!-- Images -->
<!-- <div class="mb-3">
    <label class="form-label" for="images">Images</label>
    <div class="input-group input-group-merge">
        <input type="file" name="images[]" id="images" class="form-control" multiple>
    </div>
</div> -->

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