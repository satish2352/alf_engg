@extends('superadm.layout.master')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4>Add Role</h4>
                <form action="{{ route('roles.save') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Role Name</label>
                        <input type="text" name="role" class="form-control" value="{{ old('role') }}">
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('roles.list') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
