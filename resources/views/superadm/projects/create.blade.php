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
                            <label for="plant_id">Plant</label>
                            <select name="plant_id" id="plant_id" class="form-control">
                                <option value="">Select Plant </option>
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
                            <label>Project Name</label>
                            <input type="text" name="project_name" class="form-control" value="{{ old('project_name') }}">
                            @error('project_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                         <div class="form-group">
                            <label>Project Short Description</label>
                            <input type="text" name="project_description" class="form-control" value="{{ old('project_description') }}">
                            @error('project_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                         <div class="form-group">
                            <label>Project URL</label>
                            <input type="text" name="project_url" class="form-control" value="{{ old('project_url') }}">
                            @error('project_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('projects.list') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
