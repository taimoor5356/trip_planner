<div class="mb-3">
    <label class="form-label" for="name">{{ $header_title }} Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Accommodation Name">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="building_type_id">Building Type</label>
    <div class="input-group input-group-merge">
        <select name="building_type_id" id="building_type_id" class="form-control">
            <option value="">Select Building Type</option>
            @if(!empty($buildingTypes))
            @foreach($buildingTypes as $buildingType)
            <option value="{{$buildingType->id}}" {{isset($record) ? (($record->building_type_id == $buildingType->id) ? 'selected' : '') : ''}}>{{$buildingType->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="built_id">Built Type</label>
    <div class="input-group input-group-merge">
        <select name="built_id[]" id="built_id" class="form-control select2" multiple>
            @if(!empty($builtTypes))
            @foreach($builtTypes as $builtType)
            <option value="{{$builtType->id}}" {{(isset($record) && $record->built_id) ? (in_array($builtType->id, json_decode($record->built_id)) ? 'selected' : '') : ''}}>{{$builtType->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="default_status">Default Status</label>
    <div class="input-group input-group-merge">
        <select name="default_status" id="default_status" class="form-control">
            <option value="" disabled selected>Select Status</option>
            <option value="0" {{ isset($record) ? ($record->default_status == "0" ? 'selected' : '') : '' }}>No</option>
            <option value="1" {{ isset($record) ? ($record->default_status == "1" ? 'selected' : '') : '' }}>Yes</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="category_id">Categories</label>
    <div class="input-group input-group-merge">
        <select name="category_id[]" id="category_id" class="form-control select2" multiple>
            <option value="">Select Category</option>
            @if(!empty($categories))
            @foreach($categories as $category)
            <option value="{{$category->id}}" {{(isset($record) && $record->category_id) ? (in_array($category->id, json_decode($record->category_id)) ? 'selected' : '') : ''}}>{{$category->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<!-- <div class="mb-3">
    <label class="form-label" for="room_category_id">Room Categories</label>
    <div class="input-group input-group-merge">
        <select name="room_category_id[]" id="room_category_id" class="form-control select2" multiple>
            @foreach($roomCategories as $roomCategory)
                <option value="{{ $roomCategory->id }}"
                    {{ isset($record) && $record->roomCategories->pluck('room_category_id')->contains($roomCategory->id) ? 'selected' : '' }}>
                    {{ $roomCategory->name }}
                </option>
            @endforeach
        </select>
    </div>
</div> -->

<div class="col-12 room-categories-container d-none">
    <div class="mb-3">
        <label class="form-label">Amount</label>
        <div class="card">
            <div class="card-body"></div>
        </div>
        <hr>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="property_amenities_id">Property Amenities</label>
    <div class="input-group input-group-merge">
        <select name="property_amenities_id[]" id="property_amenities_id" class="form-control select2" multiple>
            <option value="">Select Property Amenities</option>
            @if(!empty($propertyAmenities))
            @foreach($propertyAmenities as $propertyAmenity)
            <option value="{{$propertyAmenity->id}}" {{isset($record) ? (in_array($propertyAmenity->id, json_decode($record->property_amenities_id)) ? 'selected' : '') : ''}}>{{$propertyAmenity->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="location">Location</label>
    <div class="input-group input-group-merge">
        
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="town_id">Town</label>
    <div class="input-group input-group-merge">
        <select name="town_id" id="town_id" class="form-control">
            <option value="">Select Town</option>
            @if(!empty($towns))
            @foreach($towns as $town)
            <option value="{{$town->id}}" {{isset($record) ? (($record->town_id == $town->id) ? 'selected' : '') : ''}}>{{$town->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="num_of_rooms">No. of Rooms</label>
    <div class="input-group input-group-merge">
        <input type="number" name="num_of_rooms" id="num_of_rooms" placeholder="Enter No. of Rooms" class="form-control" value="{{isset($record)?$record->num_of_rooms:''}}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="front_desk_contact">Front Dest Contact</label>
    <div class="input-group input-group-merge">
        <input type="number" name="front_desk_contact" id="front_desk_contact" placeholder="Enter Front Desk Contact" class="form-control" value="{{isset($record)?$record->front_desk_contact:''}}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="sales_contact">Sales Contact</label>
    <div class="input-group input-group-merge">
        <input type="number" name="sales_contact" id="sales_contact" placeholder="Enter Sales Contact" class="form-control" value="{{isset($record)?$record->sales_contact:''}}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="fb_link">Facebook Link</label>
    <div class="input-group input-group-merge">
        <input type="text" name="fb_link" id="fb_link" placeholder="Enter Facebook Link" class="form-control" value="{{isset($record)?$record->fb_link:''}}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="insta_link">Instagram Link</label>
    <div class="input-group input-group-merge">
        <input type="text" name="insta_link" id="insta_link" placeholder="Enter Instagram Link" class="form-control" value="{{isset($record)?$record->insta_link:''}}">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="website_link">Website Link</label>
    <div class="input-group input-group-merge">
        <input type="text" name="website_link" id="website_link" placeholder="Enter Website Link" class="form-control" value="{{isset($record)?$record->website_link:''}}">
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

@section('_scripts')

<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
$(document).ready(function () {
    $('.select2').select2({
        placeholder: 'Select',
        allowClear: true
    });

    const allRoomCategories = @json($roomCategories);
    const existingRoomAmounts = @json($roomCategoryAmounts ?? []);
    const defaultRoomCategoryId = @json($roomCategoryDefault ?? null);

    function renderRoomCategoryInputs() {
        const selected = $('#room_category_id').val();
        const container = $('.room-categories-container');
        const cardBody = container.find('.card-body');
        cardBody.html('');

        if (selected && selected.length > 0) {
            container.removeClass('d-none');

            selected.forEach(id => {
                const room = allRoomCategories.find(r => r.id == id);
                if (room) {
                    const amount = existingRoomAmounts[room.id] ?? '';
                    const isChecked = room.id == defaultRoomCategoryId ? 'checked' : '';
                    const showLabel = room.id == defaultRoomCategoryId ? '' : 'd-none';

                    const html = `
                        <div class="room-group mb-3" data-id="${room.id}">
                            <label class="form-label">${room.name}</label>
                            <div class="input-group input-group-merge mb-2 align-items-center">
                                <div class="d-flex align-items-center">
                                    <input type="radio" name="room_category_default_selected" value="${room.id}" ${isChecked}>
                                </div>
                                <div class="ms-2">
                                    <input type="text" class="form-control" name="room_category_amounts[${room.id}][]" value="${amount}" placeholder="Enter amount">
                                </div>
                                <div class="ms-2 d-flex align-items-center">
                                    <span class="default-label text-success fw-bold ${showLabel}">Default</span>
                                </div>
                            </div>
                        </div>
                    `;
                    cardBody.append(html);
                }
            });
        } else {
            container.addClass('d-none');
        }
    }

    // Trigger on change and load
    $('#room_category_id').on('change', renderRoomCategoryInputs);
    renderRoomCategoryInputs();

    // Show "Default" label when radio is changed
    $(document).on('change', 'input[name="room_category_default_selected"]', function () {
        $('.default-label').addClass('d-none');
        $(this).closest('.input-group').find('.default-label').removeClass('d-none');
    });
});
</script>

@endsection