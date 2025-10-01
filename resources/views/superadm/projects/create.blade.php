@extends('superadm.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4>Add Project</h4>
                    <form action="{{ route('projects.save') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="plant_id">Plant <span class="text-danger">*</span></label>
                                <select name="plant_id" id="plant_id" class="form-control">
                                    <option value="" disabled selected>Select Plant</option>
                                    @foreach ($plants as $plant)
                                        <option value="{{ $plant->id }}"
                                            {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
                                            {{ $plant->plant_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @error('plant_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" class="form-control"
                                value="{{ old('project_name') }}">
                            @error('project_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Project Short Description</label>
                            <input type="text" name="project_description" class="form-control"
                                value="{{ old('project_description') }}">
                            @error('project_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label>Project URL <span class="text-danger">*</span></label>
                            <input type="text" name="project_url" class="form-control" value="{{ old('project_url') }}">
                            @error('project_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group d-flex justify-content-end">
                            <a href="{{ route('projects.list') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
