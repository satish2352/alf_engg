@extends('superadm.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h4>Edit Designation</h4>
                    <form action="{{ route('projects.update', $encodedId) }}" method="POST">
                        @csrf


                        <div class="form-group">
                            <label for="plant_id">Plant</label>
                            <input type="hidden" name="id" class="form-control" value="{{ old('id', $data->id) }}">
                            <select name="plant_id" id="plant_id" class="form-control">
                                <option value="">Select Plant </option>
                                @foreach ($plants as $plant)
                                    <option value="{{ $plant->id }}"
                                        {{ $data->plant_id == $plant->id ? 'selected' : '' }}
                                        {{ old('plant_id') == $plant->id ? 'selected' : '' }}
                                        >
                                        {{ $plant->plant_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plant_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                       
                        <div class="form-group">
                            <label>Project Name</label>
                            <input type="hidden" name="id" class="form-control" value="{{ old('id', $data->id) }}">
                            <input type="text" name="project_name" class="form-control"
                            value="{{ old('project_name', $data->project_name) }}">
                            @error('project_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                         <div class="form-group">
                            <label>Project Short Description</label>
                            <input type="text" name="project_description" class="form-control"
                            value="{{ old('project_description', $data->project_description) }}">
                            @error('project_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                         <div class="form-group">
                            <label>Project URL</label>
                            <input type="text" name="project_url" class="form-control"
                            value="{{ old('project_url', $data->project_url) }}">
                            @error('project_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        



                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                <option value="">select status</option>
                                <option value="1" {{ old('is_active', $data->is_active) == '1' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0" {{ old('is_active', $data->is_active) == '0' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                            @error('is_active')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('projects.list') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
