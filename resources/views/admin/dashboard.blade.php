@extends('layout.app')
@section('_styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .select2-container .select2-selection--single {
        display: block;
        width: 100%;
        height: calc(2.25rem + 2px);
        padding: .375rem .25rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .card-border-shadow-primary:after {
        border-bottom-color: #0d6efd; /* Bootstrap primary */
    }

    .card-border-shadow-warning:after {
        border-bottom-color: #ffc107; /* Bootstrap warning */
    }

    .card-border-shadow-success:after {
        border-bottom-color: #198754; /* Bootstrap success */
    }

    .card-border-shadow-danger:after {
        border-bottom-color: #dc3545;
    }

    .card-border-shadow-info:after {
        border-bottom-color: #0dcaf0;
    }
    .card[class*="card-border-shadow-"] {
        position: relative;
        border-bottom: none;
        transition: all 0.2s ease-in-out;
        z-index: 1;
    }
    .card[class*="card-border-shadow-"]:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-bottom-width: 2px;
        border-bottom-style: solid;
        border-radius: 0.375rem;
        transition: all 0.2s ease-in-out;
        z-index: -1;
    }
    .card[class*="card-border-shadow-"]:hover {
        box-shadow: 0 0.25rem 0.75rem #22303e24;
    }
    .card[class*="card-border-shadow-"]:hover:after {
        border-bottom-width: 3px;
    }
    .card[class*="card-hover-border-"] {
        border-width: 1px;
    }
</style>
@endsection
@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    @include('_messages')
    <div class="row">
        <div class="col-12 d-flex justify-content-between">
            <div class="breadcrumb-list">
                <h4 class="fw-bold py-3 mb-4">Dashboard</h4>
            </div>
        </div>
    </div>
    {{--<div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card p-4">
                <form action="">
                    <div class="row mb-3">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <label for="">Select City</label>
                            <br>
                            <select name="group_id" id="group_id" value="{{Request::get('group_id')}}" class="form-control">
                                <option value="all" selected>All</option>
                                @if (!empty($groups))
                                    @foreach ($groups as $group)
                                    <option value="{{$group->id}}" {{(!empty(Request::get('group_id')) && Request::get('group_id') == $group->id) ? 'selected' : ''}}>{{$group->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <label for="">Select Town</label>
                            <br>
                            <select name="provider_npi" id="provider_npi" value="{{Request::get('provider_npi')}}" class="form-control">
                                <option value="all" selected>All</option>
                                @if (!empty($customers))
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->provider_npi}}" {{(!empty(Request::get('provider_npi')) && Request::get('provider_npi') == $customer->provider_npi) ? 'selected' : ''}}>{{$customer->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <label for="">Select Location</label>
                            <br>
                            <select name="location" id="location" value="{{Request::get('location')}}" class="form-control">
                                <option value="all" selected>All</option>
                                @if (!empty($locations))
                                    @foreach ($locations as $location)
                                        <option value="{{$location->pos_type}}" {{(!empty(Request::get('location')) && Request::get('location') == $location->pos_type) ? 'selected' : ''}}>{{$location->pos_type}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <label for="">Start Date</label>
                            <br>
                            <input type="date" name="start_date" value="{{Request::get('start_date')}}" class="form-control">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                            <label for="">End Date</label>
                            <br>
                            <input type="date" name="end_date" value="{{Request::get('end_date')}}" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <button type="submit" class="btn btn-primary">Apply</button>
                            <a href="{{url('admin/dashboard')}}" type="submit" class="btn btn-danger">Clear All</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>--}}
    <div class="row">
        <div class="col-lg-12 col-md-12 order-1">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-border-shadow-primary">
                        <div class="card-body">
                            <span class="d-block mb-1"><i class="menu-icon tf-icons bx bx-group"></i> No. of Customers</span>
                            <h3 class="card-title text-nowrap mb-2">{{!empty($customers) ? number_format($customers->count()) : 0}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-border-shadow-warning">
                        <div class="card-body">
                            <span class="d-block mb-1"><i class="menu-icon tf-icons bx bx-file"></i> No. of Trips</span>
                            <h3 class="card-title text-nowrap mb-2">{{!empty($noOfTrips) ? number_format($noOfTrips->count()) : 0}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-border-shadow-danger">
                        <div class="card-body">
                            <span class="d-block mb-1"><i class="menu-icon tf-icons bx bx-file"></i> No. of Vehicles</span>
                            <h3 class="card-title text-nowrap mb-2">{{!empty($noOfVehicles) ? number_format($noOfVehicles->count()) : 0}}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card card-border-shadow-info">
                        <div class="card-body">
                            <span class="d-block mb-1"><i class="menu-icon tf-icons bx bx-group"></i> Total Bookings Amount</span>
                            <h3 class="card-title text-nowrap mb-2">{{!empty($totalBookingAmount) ? number_format($totalBookingAmount->count()) : 0}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            </div>
        </div>
    </div>
</div>

@endsection

@section('_scripts')
<script>
    $(document).ready(function() {
        $('select').select2();

        $(document).on('change', '#group_id', function() {
            var url = "";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    group_id: $('#group_id').val(),
                    provider_npi: '',
                    location: '',
                },
                success: function(response) {
                    if (response.status == true) {
                        $('#provider_npi').html(response.customers);
                        $('#location').html(response.locations);
                        $('#mrn_number').html(response.mrn_numbers);
                    }
                }
            });
        });

        $(document).on('change', '#provider_npi', function() {
            var url = "";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    group_id: $('#group_id').val(),
                    provider_npi: $('#provider_npi').val(),
                    location: '',
                },
                success: function(response) {
                    if (response.status == true) {
                        $('#location').html(response.locations);
                        $('#mrn_number').html(response.mrn_numbers);
                    }
                }
            });
        });

        $(document).on('change', '#location', function() {
            var url = "";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}",
                    group_id: '',
                    provider_npi: $('#provider_npi').val(),
                    location: $('#location').val(),
                },
                success: function(response) {
                    if (response.status == true) {
                        $('#mrn_number').html(response.mrn_numbers);
                    }
                }
            });
        });

    });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection