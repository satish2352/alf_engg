@extends('superadm.layout.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between">
                    <input type="text" id="searchInput" class="form-control w-50" placeholder="Search employees...">
                    <a href="{{ route('employees.create') }}" class="btn btn-danger">
                        <i class="mdi mdi-account-plus"></i> Add Employee
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Email</th>
                                <th>User Name</th>
                                <th>Reporting To</th>
                                <th>Plant Name</th>
                                <th>Project Name</th>
                                <th>Department Name</th>
                                <th>Designation</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach($employees as $key => $data)
                            <tr>
                                <td>{{ $employees->firstItem() + $key }}</td>
                                <td>{{ $data->employee_name }}</td>
                                <td>{{ $data->employee_code }}</td>
                                <td>{{ $data->employee_email }}</td>
                                <td>{{ $data->employee_user_name }}</td>
                                <td>{{ $data->reporting_name ?? '-' }}</td>
                                <td>{{ $data->plant_name ?? '-' }}</td>
                                <td>{{ $data->project_names ?? '-' }}</td>
                                <td>{{ $data->department_names ?? '-' }}</td>
                                <td>{{ $data->designation ?? '-' }}</td>
                                <td>{{ $data->role ?? '-' }}</td>
                                <td>
                                    @if ($data->role != 0)
                                    <label class="switch">
                                        <input type="checkbox" class="toggle-status " data-id="{{ base64_encode($data->id) }}" {{ $data->is_active ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                     @else 
                                      <label class="switch">
                                        <input type="checkbox" class="toggle-status " disabled>
                                        <span class="slider"></span>
                                    </label>
                                       @endif
                                </td>
                                <td class="d-flex">
                                    @if ($data->role != 0)
                                        {{-- Edit Button --}}
                                        <a href="{{ route('employees.edit', base64_encode($data->id)) }}" 
                                            class="btn btn-sm btn-primary mr-1" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="Edit">
                                            <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                data-id="{{ base64_encode($data->id) }}"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="Delete">
                                            <i class="mdi mdi-trash-can-outline icon-medium"></i>
                                        </button>
                                    @else
                                        {{-- Disabled Buttons --}}
                                        <button class="btn btn-sm btn-secondary mr-1" disabled>
                                            <i class="mdi mdi-square-edit-outline icon-medium"></i>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="mdi mdi-trash-can-outline icon-medium"></i>
                                        </button>
                                    @endif
                                </td>                               
                            </tr>
                            @endforeach
                            @if($employees->count() == 0)
                            <tr><td colspan="13" class="text-center">No employees found.</td></tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="mt-3" id="paginationLinks">
                        {{ $employees->links('pagination::bootstrap-4') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    // Function to render table rows
    function renderTable(data = [], currentPage = 1, perPage = 10) {
        let html = '';
        if(data.length > 0){
            data.forEach(function(emp, index){
                // Serial number calculation per page
                let srNo = (currentPage - 1) * perPage + index + 1;

                html += `<tr>
                    <td>${srNo}</td>
                    <td>${emp.employee_name}</td>
                    <td>${emp.employee_code}</td>
                    <td>${emp.employee_email}</td>
                    <td>${emp.employee_user_name}</td>
                    <td>${emp.reporting_name ?? '-'}</td>
                    <td>${emp.plant_name ?? '-'}</td>
                    <td>${emp.project_names ?? '-'}</td>
                    <td>${emp.department_names ?? '-'}</td>
                    <td>${emp.designation ?? '-'}</td>
                    <td>${emp.role ?? '-'}</td>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="toggle-status" data-id="${btoa(emp.id)}" ${emp.is_active ? 'checked' : ''}>
                            <span class="slider"></span>
                        </label>
                    </td>
                    <td>
                        <a href="/employees/edit/${btoa(emp.id)}" class="btn btn-sm btn-primary mr-1" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="mdi mdi-square-edit-outline"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${btoa(emp.id)}"  data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </button>
                    </td>
                </tr>`;
            });
        } else {
            html = '<tr><td colspan="13" class="text-center">No employees found.</td></tr>';
        }
        return html;
    }

    // Fetch employees AJAX
    function fetchEmployees(url = "{{ route('employees.ajax') }}", search = '') {
        $.ajax({
            url: url,
            type: 'GET',
            data: { search: search },
            success: function(res){
                let employeesData = res.data ?? [];
                let currentPage = res.current_page ?? 1;
                let perPage = res.per_page ?? 10;

                $('#tableBody').html(renderTable(employeesData, currentPage, perPage));
                $('#paginationLinks').html(res.pagination ?? '');

                // Re-initialize Bootstrap tooltips after AJAX render
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function(xhr){
                console.error(xhr.responseText);
                $('#tableBody').html('<tr><td colspan="13" class="text-danger text-center">Failed to load data</td></tr>');
            }
        });
    }

    // Search typing
    let typingTimer;
    let doneTypingInterval = 400;
    $('#searchInput').on('keyup', function(){
        clearTimeout(typingTimer);
        let search = $(this).val();
        typingTimer = setTimeout(function(){
            fetchEmployees("{{ route('employees.ajax') }}", search);
        }, doneTypingInterval);
    });

    // Pagination click
    $(document).on('click', '.pagination a', function(e){
        e.preventDefault();
        let url = $(this).attr('href');
        url = url.replace("{{ route('employees.list') }}", "{{ route('employees.ajax') }}");
        let search = $('#searchInput').val();
        fetchEmployees(url, search);
    });

    // Toggle status
    $(document).on('change', '.toggle-status', function(){
        let id = $(this).data('id'); // base64 encoded
        let isActive = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("employees.updatestatus") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                is_active: isActive
            },
            success: function(res){
                alert(res.message);
            },
            error: function(xhr){
                alert('Failed to update status');
            }
        });
    });

    // Delete employee
    // $(document).on('click', '.delete-btn', function(){
    //     if(!confirm('Are you sure you want to delete this employee?')) return;
    //     let id = $(this).data('id'); // base64 encoded

    //     $.ajax({
    //         url: '{{ route("employees.delete") }}',
    //         type: 'POST',
    //         data: {
    //             _token: '{{ csrf_token() }}',
    //             id: id
    //         },
    //         success: function(res){
    //             alert(res.message);
    //             fetchEmployees("{{ route('employees.ajax') }}", $('#searchInput').val());
    //         },
    //         error: function(xhr){
    //             alert('Failed to delete employee');
    //         }
    //     });
    // });

    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id'); // base64 encoded

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("employees.delete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(res){
                         Swal.fire('Deleted!', res.message, 'success').then(() => {
                            location.reload(); 
                        });
                    },
                    error: function(xhr){
                        Swal.fire('Failed!', 'Failed to delete employee', 'error');
                    }
                });
            }
        });
    });


});
</script>


@endsection
