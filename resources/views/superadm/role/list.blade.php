@extends('superadm.layout.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-3">
                    <a href="{{ route('roles.create') }}" class="btn btn-warning">Add Role</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table  class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sr.No.</th>
                                <th>Role Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $role->role }}</td>
                                    <td>{{ $role->is_active == '1' ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('roles.edit', base64_encode($role->id))  }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('roles.delete') }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ base64_encode($role->id)  }}">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this role?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
