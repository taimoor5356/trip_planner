<div class="mb-3">
    <label class="form-label" for="origin_id">Origins</label>
    <div class="input-group input-group-merge">
        <select name="origin_id" id="origin_id" class="form-control">
            <option value="" disabled selected>Select Oigin</option>
            @if(!empty($origins))
            @foreach($origins as $origin)
            <option value="{{$origin->id}}" {{ isset($record) ? ($record->origin_id == $origin->id ? 'selected' : '') : '' }}>{{$origin->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="season_id">Seasons</label>
    <div class="input-group input-group-merge">
        <select name="season_id[]" id="season_id" class="form-control select2" multiple>
            <option value="" disabled selected>Select Season</option>
            @if(!empty($seasons))
            @foreach($seasons as $season)
            <option value="{{$season->id}}" {{ isset($record) ? ($record->season_id == $season->id ? 'selected' : '') : '' }}>{{$season->name}}</option>
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