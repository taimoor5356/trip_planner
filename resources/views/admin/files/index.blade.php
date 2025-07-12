@extends('layout.app')
@section('_styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
@endsection
@section('content')

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12 d-flex justify-content-between">
            <div class="breadcrumb-list">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Files /</span> List</h4>
            </div>
        </div>
    </div>
    <!-- Responsive Table -->
    <div class="card">
        <h5 class="card-header">Files</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table data-table display responsive nowrap" width="100%">
                    <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>Name</th>
                            <th>Path</th>
                            <th>Download</th>
                            <th>Read/Unread</th>
                            <th>Created By</th>
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
<script>

$(document).ready(function() {
    $(document).ready(function() {
        var table = $('table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{url('admin/files')}}",
            },
            columns: [
                {
                    name: 'sr_no',
                    data: 'sr_no'
                },
                {
                    name: 'name',
                    data: 'name'
                },
                {
                    name: 'path',
                    data: 'path'
                },
                {
                    name: 'download',
                    data: 'download'
                },
                {
                    name: 'read',
                    data: 'read'
                },
                {
                    name: 'created_by',
                    data: 'created_by'
                },
                {
                    className: 'text-center',
                    name: 'actions',
                    data: 'actions'
                },
            ],
            createdRow: function(row, data, dataIndex) {
                var index = dataIndex + 1; // Start from 1
                $('td', row).eq(0).text(index); // Update the first cell of the row
            }
        });
        // After initializing DataTables, call feather.replace()
        table.on('draw', function() {
            feather.replace();
        });
    });
});
</script>
@endsection