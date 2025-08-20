@extends('superadm.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4>Add Employee</h4>
                    <form action="{{ route('employees.save') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="plant_id">Plant</label>
                            <select name="plant_id" id="plant_id" class="form-control">
                                <option value="">Select Plant </option>
                                @foreach ($plants as $plant)
                                    <option value="{{ $plant->id }}"
                                        {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
                                        {{ $plant->plant_name }}
                                    </option>
                            </select>
                            @error('plant_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="department_id">Department</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="">Select Department </option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="designation_id">Designation</label>
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

                        <div class="form-group">
                            <label for="role_id">Role</label>
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
                        </div>

                        <div class="form-group">
                            <label for="role_id">Employee Type</label>
                            <select name="employee_type" id="employee_type" class="form-control">
                                <option value="">Select Role </option>
                                <option value="test_1">Test 1</option>
                                <option value="test_2">Test 2</option>
                                @endforeach
                            </select>
                            @error('employee_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Employee Code</label>
                            <input type="text" name="employee_code" class="form-control"
                                value="{{ old('employee_code') }}">
                            @error('employee_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label>Employee Name</label>
                            <input type="text" name="employee_name" class="form-control"
                                value="{{ old('employee_name') }}">
                            @error('employee_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="employee_email" class="form-control"
                                value="{{ old('employee_email') }}">
                            @error('employee_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" name="employee_user_name" class="form-control"
                                value="{{ old('employee_user_name') }}">
                            @error('employee_user_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" name="employee_password" class="form-control"
                                value="{{ old('employee_password') }}">
                            @error('employee_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('employees.list') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
