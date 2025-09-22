@extends('superadm.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4>Add Employee</h4>
                    <form action="{{ route('employees.save') }}" method="POST">
                        @csrf

                        {{-- Plant --}}
                        <div class="form-group">
                            <label for="plant_id">Select Plant <span class="text-danger">*</span></label>
                            <select name="plant_id" id="plant_id" class="form-control">
                                <option value="">Select Plant </option>
                                @foreach ($plants as $plant)
                                   <option value="{{ $plant->id }}"
                                        {{ isset($employee) && $employee->plant_id == $plant->id ? 'selected' : '' }}>
                                        {{ $plant->plant_name }}
                                   </option>
                                    {{-- <option value="{{ $plant->id }}"
                                        {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
                                        {{ $plant->plant_name }}
                                    </option> --}}

                                @endforeach
                            </select>
                            @error('plant_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="projects_id">Select Project <span class="text-danger">*</span></label>
                            <select id="projects_id" name="projects_id[]" multiple="multiple" class="form-control">
                                <!-- Options will be appended here by AJAX -->
                            </select>
                            @error('projects_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="department_id">Select Department <span class="text-danger">*</span></label>
                            <select id="department_id" name="department_id[]" multiple="multiple" class="form-control">
                                <!-- Options will be appended here by AJAX -->
                            </select>
                            @error('department_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- Designation --}}
                        <div class="form-group">
                            <label for="designation_id">Designation <span class="text-danger">*</span></label>
                            <select name="designation_id" id="designation_id" class="form-control">
                                <option value="">Select Designation </option>
                                @foreach ($designations as $designation)
                                    <option value="{{ $designation->id }}"
                                        {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                        {{ $designation->designation }}
                                    </option>
                                @endforeach
                            </select>
                            @error('designation_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Role --}}
                        {{-- <div class="form-group">
                            <label for="role_id">Role <span class="text-danger">*</span></label>
                            <select name="role_id" id="role_id" class="form-control">
                                <option value="">Select Role </option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
<div class="form-group">
    <label for="role_id">Role <span class="text-danger">*</span></label>
    <select name="role_id" id="role_id" class="form-control">
        <option value="" {{ old('role_id') === null || old('role_id') === '' ? 'selected' : '' }}>
            Select Role
        </option>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}"
                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                {{ $role->role }}
            </option>
        @endforeach
    </select>
    @error('role_id')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

                        {{-- Employee Type --}}
                        <div class="form-group">
                            <label for="employee_type">Employee Type <span class="text-danger">*</span></label>
                            <select name="employee_type" id="employee_type" class="form-control">
                                <option value="">Select Type </option>
                                <option value="test_1" {{ old('employee_type') == 'test_1' ? 'selected' : '' }}>Test 1
                                </option>
                                <option value="test_2" {{ old('employee_type') == 'test_2' ? 'selected' : '' }}>Test 2
                                </option>
                            </select>
                            @error('employee_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Employee Code --}}
                        <div class="form-group">
                            <label>Employee Code <span class="text-danger">*</span></label>
                            <input type="text" name="employee_code" class="form-control"
                                value="{{ old('employee_code') }}">
                            @error('employee_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Employee Name --}}
                        <div class="form-group">
                            <label>Employee Name <span class="text-danger">*</span></label>
                            <input type="text" name="employee_name" class="form-control"
                                value="{{ old('employee_name') }}">
                            @error('employee_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="text" name="employee_email" class="form-control"
                                value="{{ old('employee_email') }}">
                            @error('employee_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div class="form-group">
                            <label>User Name <span class="text-danger">*</span></label>
                            <input type="text" name="employee_user_name" class="form-control"
                                value="{{ old('employee_user_name') }}">
                            @error('employee_user_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="text" name="employee_password" class="form-control"
                                value="{{ old('employee_password') }}">
                            @error('employee_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="reporting_to">Reporting To</label>
                            <select name="reporting_to" id="reporting_to" class="form-control">
                                <option value="">Select Reporting To </option>
                            </select>
                            @error('reporting_to')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Reporting To --}}
                        {{-- <div class="form-group">
                            <label for="reporting_to">Reporting To</label>
                            <select name="reporting_to" id="reporting_to" class="form-control">
                                <option value="">Select Reporting To </option>
                            </select>
                            @error('reporting_to')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>  --}}
                        {{-- Reporting To --}}
                        {{-- <div class="form-group" id="reporting_to_wrapper" >
                            <label for="reporting_to">Reporting To <span class="text-danger"
                                    id="reporting_to_required">*</span></label>
                            <select name="reporting_to" id="reporting_to" class="form-control">
                                <option value="">Select Reporting To </option>
                            </select>
                            @error('reporting_to')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <div class="form-group d-flex justify-content-end">
                            <a href="{{ route('employees.list') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Bootstrap Multiselect CSS & JS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect/dist/css/bootstrap-multiselect.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect/dist/js/bootstrap-multiselect.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#projects_id').multiselect({
                includeSelectAllOption: true, // "Select All" option
                enableFiltering: true, // Search box
                maxHeight: 300, // Scrollable
                buttonWidth: '100%'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize multiselect first
            $('#projects_id').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                maxHeight: 300,
                buttonWidth: '100%'
            });

            // On Plant Change
            $('#plant_id').on('change', function() {
                let plantId = $(this).val();
                if (!plantId) {
                    $('#projects_id').empty().multiselect('rebuild');
                    return;
                }

                $.ajax({
                    url: "{{ route('projects.list-ajax') }}", // Laravel route
                    type: "POST", // POST because we are sending plant_id
                    data: {
                        _token: "{{ csrf_token() }}", // CSRF token
                        plant_id: plantId
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        $('#projects_id').empty(); // clear old options

                        if (response.projects && response.projects.length > 0) {
                            $.each(response.projects, function(key, project) {
                                $('#projects_id').append(
                                    `<option value="${project.id}">${project.project_name}</option>`
                                );
                            });
                        } else if (response.projects.length == 0) {
                            alert("No projects found");
                        }

                        // Refresh multiselect to show new options
                        $('#projects_id').multiselect('rebuild');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize multiselect first
            $('#department_id').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                maxHeight: 300,
                buttonWidth: '100%'
            });

            // On Plant Change
            $('#plant_id').on('change', function() {
                let plantId = $(this).val();
                if (!plantId) {
                    $('#department_id').empty().multiselect('rebuild');
                    return;
                }

                $.ajax({
                    url: "{{ route('departments.list-ajax') }}", // Laravel route
                    type: "POST", // POST because we are sending plant_id
                    data: {
                        _token: "{{ csrf_token() }}", // CSRF token
                        plant_id: plantId
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        $('#department_id').empty(); // clear old options

                        if (response.department && response.department.length > 0) {
                            $.each(response.department, function(key, department_result) {
                                $('#department_id').append(
                                    `<option value="${department_result.id}">${department_result.department_name}</option>`
                                );
                            });
                        } else if (response.department.length == 0) {
                            alert("No departments found");
                        }

                        // Refresh multiselect to show new options
                        $('#department_id').multiselect('rebuild');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
   <script>
        $(document).ready(function() {
            // Initialize multiselect first
            $('#reporting_to').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                maxHeight: 300,
                buttonWidth: '100%'
            });

            // On Plant Change
            $('#plant_id').on('change', function() {
                let plantId = $(this).val();
                if (!plantId) {
                    $('#reporting_to').empty().multiselect('rebuild');
                    return;
                }

                $.ajax({
                    url: "{{ route('employees.list-ajax') }}", // Laravel route
                    type: "POST", // POST because we are sending plant_id
                    data: {
                        _token: "{{ csrf_token() }}", // CSRF token
                        plant_id: plantId
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        $('#reporting_to').empty(); // clear old options
                        $('#reporting_to').append(
                                    `<option value="">select name</option>`
                                );

                        if (response.employees && response.employees.length > 0) {
                            $.each(response.employees, function(key, employees_result) {
                                $('#reporting_to').append(
                                    `<option value="${employees_result.id}">${employees_result.employee_name}</option>`
                                );
                            });
                        } else if (response.employees.length == 0) {
                            alert("No employees found");
                        }

                        // Refresh multiselect to show new options
                        $('#reporting_to').multiselect('rebuild');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            // Initialize multiselect
            $('#reporting_to').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                maxHeight: 300,
                buttonWidth: '100%'
            });

            // On Plant Change
            $('#plant_id').on('change', function() {
                let plantId = $(this).val();
                if (!plantId) {
                    $('#reporting_to').empty().multiselect('rebuild');
                    // $('#reporting_to_wrapper').hide();
                    // $('#reporting_to').prop('required', false); // remove validation
                    return;
                }

                $.ajax({
                    url: "{{ route('employees.list-ajax') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        plant_id: plantId
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        $('#reporting_to').empty();
                        $('#reporting_to').append(
                            `<option value="">Select Reporting To</option>`);

                        if (response.employees && response.employees.length > 0) {
                            $.each(response.employees, function(key, employees_result) {
                                $('#reporting_to').append(
                                    `<option value="${employees_result.id}">${employees_result.employee_name}</option>`
                                );
                            });

                            // $('#reporting_to_wrapper').show();
                            // $('#reporting_to').prop('required',
                            // true); // ✅ add validation when employees exist
                        // } 
                        // else {
                            // $('#reporting_to_wrapper').hide();
                            // $('#reporting_to').prop('required',
                            // false); // ✅ no validation when no employees
                        // }

                        $('#reporting_to').multiselect('rebuild');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script> --}}
@endsection
