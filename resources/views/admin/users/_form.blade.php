<div class="mb-3">
    <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
    <div class="input-group input-group-merge">
        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
        <input type="text" name="name" value="{{isset($record) ? $record->name : ''}}" class="form-control" id="basic-icon-default-fullname" placeholder="Enter name" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="basic-icon-default-email">Email</label>
    <div class="input-group input-group-merge">
        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
        <input type="text" name="email" value="{{isset($record) ? $record->email : ''}}" id="basic-icon-default-email" class="form-control" placeholder="Enter email" aria-label="john.doe" aria-describedby="basic-icon-default-email2">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="basic-icon-default-mobile_number">Mobile Number</label>
    <div class="input-group input-group-merge">
        <span class="input-group-text"><i class="bx bx-phone"></i></span>
        <input type="number" name="mobile_number" value="{{isset($record) ? $record->mobile_number : ''}}" id="basic-icon-default-mobile_number" class="form-control" placeholder="Enter mobile number" aria-describedby="basic-icon-default-mobile_number">
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="role_id">Roles</label>
    <div class="input-group input-group-merge">
        <select name="role_id" id="role_id" class="form-control">
            <option value="">Select Role</option>
            @if(!empty($roles))
            @foreach($roles as $role)
            <option value="{{$role->id}}" {{isset($record) ? (($record->user_type == $role->id) ? 'selected' : '') : ''}}>{{$role->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="password">Password</label>
    <div class="input-group input-group-merge">
        <span id="password2" class="input-group-text"><i class="bx bx-hide"></i></span>
        <input type="password" name="password" id="password" class="form-control phone-mask" placeholder="Enter password" aria-label="658 799 8941" aria-describedby="password2">
    </div>
</div>