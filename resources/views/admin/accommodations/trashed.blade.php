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
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ $header_title }} </span> List</h4>
            </div>
            <div class="butns">
            </div>
        </div>
    </div>
    <!-- Responsive Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table data-table display responsive nowrap" width="100%">
                    <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>Name</th>
                            <th>Building Type</th>
                            <th>Built Type</th>
                            <th>Default Status</th>
                            <th>Category</th>
                            <th>Amenities</th>
                            <th>Location</th>
                            <th>Town</th>
                            <th>Rooms</th>
                            <th>Front Desk Contact</th>
                            <th>Sales Contact</th>
                            <th>Facebook Link</th>
                            <th>Instagram Link</th>
                            <th>Website Link</th>
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
                        <a class="btn btn-sm btn-primary" href="{{asset('assets/files/accommodation-sample.csv')}}" download="accommodation-sample.csv">Download Sample</a>
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
        var table = $('table'); // Select the table element

        // Check if DataTable is already initialized
        if (!$.fn.dataTable.isDataTable(table)) {
            // Initialize the DataTable
            var table = table.DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{url('admin/accommodations/trashed')}}",
                },
                order: [],
                columns: [{
                        name: 'sr_no',
                        data: 'sr_no'
                    },
                    {
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'building_type_id',
                        data: 'building_type_id'
                    },
                    {
                        name: 'built_id',
                        data: 'built_id'
                    },
                    {
                        name: 'default_status',
                        data: 'default_status'
                    },
                    {
                        name: 'category_id',
                        data: 'category_id'
                    },
                    {
                        name: 'property_amenities_id',
                        data: 'property_amenities_id'
                    },
                    {
                        name: 'location',
                        data: 'location'
                    },
                    {
                        name: 'town_id',
                        data: 'town_id'
                    },
                    {
                        name: 'num_of_rooms',
                        data: 'num_of_rooms'
                    },
                    {
                        name: 'front_desk_contact',
                        data: 'front_desk_contact'
                    },
                    {
                        name: 'sales_contact',
                        data: 'sales_contact'
                    },
                    {
                        name: 'fb_link',
                        data: 'fb_link'
                    },
                    {
                        name: 'insta_link',
                        data: 'insta_link'
                    },
                    {
                        name: 'website_link',
                        data: 'website_link'
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
            $('.accommodation-checkbox').each(function() {
                $(this).prop('checked', $(this).is(':checked')? false : true);
                $('#delete-selected-accommodations').addClass('disabled');
                $('.accommodation-checkbox:checked').each(function() {
                    $('#delete-selected-accommodations').removeClass('disabled');
                });
            });
        });

        $(document).on('click', '.accommodation-checkbox', function() {
            if ($(this).prop('checked')) {
                $('#delete-selected-accommodations').removeClass('disabled');
            } else {
                var checkedRemaining = 0;
                $('.accommodation-checkbox').each(function() {
                    if ($(this).prop('checked')) {
                        checkedRemaining = 1;
                    }
                });
                if (checkedRemaining === 0) {
                    $('#delete-selected-accommodations').addClass('disabled');
                }
            }
        });

        $(document).on('click', '#delete-selected-accommodations', function () {
            var selectedAccommodations = [];
            $('.accommodation-checkbox:checked').each(function() {
                selectedAccommodations.push($(this).attr('data-accommodation-id'));
            });
            if (selectedAccommodations.length > 0) {
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
                            url: "{{route('delete_multiple_accommodations')}}",
                            method: 'POST',
                            data: {
                                _token: "{{csrf_token()}}",
                                accommodation_ids: selectedAccommodations 
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
                                        'Failed to delete selected accommodations.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            } else {
                Swal.fire(
                    'No accommodation selected',
                    'Please select at least one accommodation to delete.',
                    'info'
                );
            }
        });

        $(document).on('click', '.delete-accommodation', function () {
            var selectedAccommodations = $(this).attr('data-accommodation-id');
            if (selectedAccommodations.length > 0) {
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
                            url: "{{route('admin.accommodations.destroy')}}",
                            method: 'POST',
                            data: {
                                _token: "{{csrf_token()}}",
                                data_id: selectedAccommodations 
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
                    'No accommodation selected',
                    'Please select at least one accommodation to delete.',
                    'info'
                );
            }
        });


        var ajaxRequest;

        $('#importForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            var form = $(this)[0];
            var formData = new FormData(form);

            // Show spinner and disable buttons
            $('#importBtn').prop('disabled', true);
            $('#file').prop('disabled', true);
            $('#loadingText').html('<p class=\'py-3\'>Please don\'t refresh the page. Importing is in progress...</p>');
            $('.spinner-border').addClass('d-block').removeClass('d-none');

            // Make AJAX request
            ajaxRequest = $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Handle success response
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.error(xhr.responseText);
                },
                complete: function () {
                    // Enable buttons and hide spinner
                    $('#importBtn').prop('disabled', false);
                    $('#file').prop('disabled', false);
                    // $('#file').val('');
                    $('#loadingText').empty();
                    $('.spinner-border').removeClass('d-block').addClass('d-none');
                }
            });
        });

        // Show confirmation dialog when attempting to close the window or tab
        window.addEventListener('beforeunload', function (e) {
            if (ajaxRequest && ajaxRequest.readyState !== 4) {
                var confirmationMessage = 'You have an active import in progress. Are you sure you want to leave?';
                e.returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });
    });


</script>
@endsection