<div class="mb-3">
    <label class="form-label" for="accommodation_id">Accommodations</label>
    <div class="input-group input-group-merge">
        <select name="accommodation_id" id="accommodation_id" class="form-control">
            <option value="" disabled selected>Select Accommodation</option>
            @if(!empty($accommodations))
            @foreach($accommodations as $accommodation)
            <option value="{{$accommodation->id}}" {{ isset($record) ? ($record->accommodation_id == $accommodation->id ? 'selected' : '') : '' }}>{{$accommodation->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="room_amenity_id">Room Amenities</label>
    <div class="input-group input-group-merge">
        <select name="room_amenity_id" id="room_amenity_id" class="form-control">
            <option value="" disabled selected>Select Room Amenity</option>
            @if(!empty($roomAmenities))
            @foreach($roomAmenities as $roomAmenity)
            <option value="{{$roomAmenity->id}}" {{ isset($record) ? ($record->roomAmenity_id == $roomAmenity->id ? 'selected' : '') : '' }}>{{$roomAmenity->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="room_category_id">Room Categories</label>
    <div class="input-group input-group-merge">
        <select name="room_category_id" id="room_category_id" class="form-control">
            <option value="" disabled selected>Select Room Category</option>
            @if(!empty($roomCategories))
            @foreach($roomCategories as $roomCategory)
            <option value="{{$roomCategory->id}}" {{ isset($record) ? ($record->roomCategory_id == $roomCategory->id ? 'selected' : '') : '' }}>{{$roomCategory->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="from">From</label>
    <div class="input-group input-group-merge">
        <input type="date" name="from" id="from" class="form-control" value="{{isset($record)?$record->from:''}}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="to">To</label>
    <div class="input-group input-group-merge">
        <input type="date" name="to" id="to" class="form-control" value="{{isset($record)?$record->to:''}}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="price">Price</label>
    <div class="input-group input-group-merge">
        <input type="number" name="price" id="price" placeholder="Enter Price" class="form-control" value="{{isset($record)?$record->price:''}}">
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