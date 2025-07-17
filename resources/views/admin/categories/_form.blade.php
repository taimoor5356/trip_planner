<div class="mb-3">
    <label class="form-label" for="name">Category Name</label>
    <div class="input-group input-group-merge">
        <input type="text" name="name" value="{{isset($record)?$record->name:''}}" class="form-control" placeholder="Enter Category Name">
    </div>
</div>
<!-- <div class="mb-3">
    <label class="form-label" for="price">Price</label>
    <div class="input-group input-group-merge">
        <input type="number" name="price" value="{{isset($record)?$record->price:''}}" class="form-control" placeholder="Enter Price">
    </div>
</div> -->
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