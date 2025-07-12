@extends('layout.app')
@section('_styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .butns {
        width: auto !important;
        display: flex !important;
        align-items: baseline;
    }
</style>
@endsection
@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    @include('_messages')
    <div class="row">
        <div class="col-12 d-flex justify-content-between">
            <div class="breadcrumb-list">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Vehicles /</span> List</h4>
            </div>
            <div class="butns">
                <a href="{{url('admin/vehicles/create')}}" class="btn btn-primary ms-2">Add New</a>
                <!-- <a href="{{url('admin/vehicles/sync')}}" class="btn btn-success">Sync</a> -->
                <a href="{{url('admin/vehicles/trashed')}}" class="btn btn-danger ms-2"ms-2>Trashed</a>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal"
                    data-bs-target="#importData">
                    Import
                </button>
            </div>
        </div>
    </div>
    <!-- Responsive Table -->
    <div class="card">
        <h5 class="card-header">Vehicles</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table data-table display responsive nowrap" width="100%">
                    <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>Name</th>
                            <th>Registration No</th>
                            <th>Capacity Adults</th>
                            <th>Capacity Children</th>
                            <th>Infants</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>City</th>
                            <th>Per Day Cost</th>
                            <th>Vehicle Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Responsive Table -->
    <!-- Modal -->
    <div class="modal fade" id="importData" tabindex="-1"
        aria-labelledby="importDataLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h1 class="modal-title fs-5" id="importDataLabel">Import</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-sm btn-primary" href="{{asset('assets/files/vehicle-sample.csv')}}" download="vehicle-sample.csv">Download Sample</a>
                    </div>
                    <form action="{{ url('admin/'.$url_segment_two.'/import') }}" method="post"
                        enctype="multipart/form-data" class="row" id="importForm">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label" for="file">Choose Excel
                                file:</label>
                            <input type="file" class="form-control" name="file"
                                accept=".xlsx, .xls, .csv" id="file" required>
                        </div>
                        <div class="col-12" id="loadingText">

                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success"
                                id="importBtn">
                                Import
                            </button>
                            {{-- Spinner --}}
                            <div class="spinner-border float-end d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('_scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).ready(function() {
            var table = $('table'); // Select the table element

            // Check if DataTable is already initialized
            if (!$.fn.dataTable.isDataTable(table)) {
                // Initialize the DataTable
                var table = table.DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    ajax: {
                        url: "{{url('admin/vehicles/list')}}",
                    },
                    columns: [{
                            name: 'sr_no',
                            data: 'sr_no'
                        },
                        {
                            name: 'name',
                            data: 'name'
                        },
                        {
                            name: 'registration_number',
                            data: 'registration_number'
                        },
                        {
                            name: 'capacity_adults',
                            data: 'capacity_adults'
                        },
                        {
                            name: 'capacity_children',
                            data: 'capacity_children'
                        },
                        {
                            name: 'infants',
                            data: 'infants'
                        },
                        {
                            name: 'brand',
                            data: 'brand'
                        },
                        {
                            name: 'model',
                            data: 'model'
                        },
                        {
                            name: 'region_name',
                            data: 'region_name'
                        },
                        {
                            name: 'per_day_cost',
                            data: 'per_day_cost'
                        },
                        {
                            name: 'vehicle_type_name',
                            data: 'vehicle_type_name'
                        },
                        {
                            name: 'status',
                            data: 'status'
                        },
                        {
                            name: 'created_at',
                            data: 'created_at'
                        },
                        {
                            className: 'text-center',
                            name: 'actions',
                            data: 'actions'
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {
                        var index = dataIndex + 1; // Start from 1
                        $('td', row).eq(0).text(index); // Update the first cell of the row
                    }
                });
            } else {
                console.log("DataTable is already initialized.");
            }
            // After initializing DataTables, call feather.replace()
            table.on('draw', function() {
                feather.replace();
            });

            $(document).on('click', '#check-all', function() {
                $('.vehicle-checkbox').each(function() {
                    $(this).prop('checked', $(this).is(':checked')? false : true);
                    $('#delete-selected-vehicles').addClass('disabled');
                    $('.vehicle-checkbox:checked').each(function() {
                        $('#delete-selected-vehicles').removeClass('disabled');
                    });
                });
            });

            $(document).on('click', '.vehicle-checkbox', function() {
                if ($(this).prop('checked')) {
                    $('#delete-selected-vehicles').removeClass('disabled');
                } else {
                    var checkedRemaining = 0;
                    $('.vehicle-checkbox').each(function() {
                        if ($(this).prop('checked')) {
                            checkedRemaining = 1;
                        }
                    });
                    if (checkedRemaining === 0) {
                        $('#delete-selected-vehicles').addClass('disabled');
                    }
                }
            });

            $(document).on('click', '#delete-selected-vehicles', function () {
                var selectedVehicles = [];
                $('.vehicle-checkbox:checked').each(function() {
                    selectedVehicles.push($(this).attr('data-vehicle-id'));
                });
                if (selectedVehicles.length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        title: 'Are you sure to DELETE?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: 'green',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{route('delete_multiple_vehicles')}}",
                                method: 'POST',
                                data: {
                                    _token: "{{csrf_token()}}",
                                    data_ids: selectedVehicles 
                                },
                                success: function(response) {
                                    if (response.status) {
                                        Swal.fire(
                                            'Deleted!',
                                            response.message,
                                            'success'
                                        );
                                        table.draw(false);
                                    } else {
                                        Swal.fire(
                                            'Failed!',
                                            'Failed to delete selected vehicles.',
                                            'error'
                                        );
                                    }
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire(
                        'No vehicle selected',
                        'Please select at least one vehicle to delete.',
                        'info'
                    );
                }
            });

            $(document).on('click', '.delete-vehicle', function () {
                var selectedVehicle = $(this).attr('data-vehicle-id');
                if (selectedVehicle.length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        title: 'Are you sure to DELETE?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: 'green',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{route('admin.vehicles.destroy')}}",
                                method: 'POST',
                                data: {
                                    _token: "{{csrf_token()}}",
                                    data_id: selectedVehicle 
                                },
                                success: function(response) {
                                    if (response.status) {
                                        Swal.fire(
                                            'Deleted!',
                                            response.message,
                                            'success'
                                        );
                                        table.draw(false);
                                    } else {
                                        Swal.fire(
                                            'Failed!',
                                            response.message,
                                            'error'
                                        );
                                    }
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire(
                        'No vehicle selected',
                        'Please select at least one vehicle to delete.',
                        'info'
                    );
                }
            });
        });
    });
</script>
@endsection