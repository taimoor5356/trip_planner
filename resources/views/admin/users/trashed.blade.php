@extends('layout.app')
@section('_styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection
@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    @include('_messages')
    <div class="row">
        <div class="col-12 d-flex justify-content-between">
            <div class="breadcrumb-list">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Trashed</h4>
            </div>
            <div class="butns">
                <a href="{{url('admin/users')}}" class="btn btn-primary">View All</a>
            </div>
        </div>
    </div>
    <!-- Responsive Table -->
    <div class="card">
        <h5 class="card-header">Users</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table data-table display responsive nowrap" width="100%">
                    <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Group</th>
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
                        url: "{{url('admin/users/trashed')}}",
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
                            name: 'email',
                            data: 'email'
                        },
                        {
                            name: 'role',
                            data: 'role'
                        },
                        {
                            name: 'group',
                            data: 'group'
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
                $('.user-checkbox').each(function() {
                    $(this).prop('checked', $(this).is(':checked')? false : true);
                    $('#delete-selected-users').addClass('disabled');
                    $('.user-checkbox:checked').each(function() {
                        $('#delete-selected-users').removeClass('disabled');
                    });
                });
            });

            $(document).on('click', '.user-checkbox', function() {
                if ($(this).prop('checked')) {
                    $('#delete-selected-users').removeClass('disabled');
                } else {
                    var checkedRemaining = 0;
                    $('.user-checkbox').each(function() {
                        if ($(this).prop('checked')) {
                            checkedRemaining = 1;
                        }
                    });
                    if (checkedRemaining === 0) {
                        $('#delete-selected-users').addClass('disabled');
                    }
                }
            });

            $(document).on('click', '#delete-selected-users', function () {
                var selectedUsers = [];
                $('.user-checkbox:checked').each(function() {
                    selectedUsers.push($(this).attr('data-user-id'));
                });
                if (selectedUsers.length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: 'green',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{route('delete_multiple_users')}}",
                                method: 'POST',
                                data: {
                                    _token: "{{csrf_token()}}",
                                    user_ids: selectedUsers 
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
                                            'Failed to delete selected users.',
                                            'error'
                                        );
                                    }
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire(
                        'No user selected',
                        'Please select at least one user to delete.',
                        'info'
                    );
                }
            });
        });
    });
</script>
@endsection