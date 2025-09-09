@extends('superadm.layout.master')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-8 mx-auto">
        <div class="page-header">
            <h3 class="page-title">Change Password</h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" action="{{ url('update-password') }}" method="POST" enctype="multipart/form-data" id="regForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <!-- New Password -->
                                    <div class="form-group position-relative">
                                        <label for="new_password">New Password</label>&nbsp;
                                        <input type="password" class="form-control mb-2 @error('new_password') is-invalid @enderror" name="new_password" id="new_password" placeholder="Enter New Password" value="{{ old('new_password') }}">
                                        <i class="fa fa-eye password-toggle" onclick="togglePassword('new_password')" style="position:absolute; right:10px; top:38px; cursor:pointer;"></i>
                                        @error('new_password')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group position-relative">
                                        <label for="confirm_password">Confirm Password</label>&nbsp;
                                        <input type="password" class="form-control mb-2 @error('confirm_password') is-invalid @enderror" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="{{ old('confirm_password') }}">
                                        <i class="fa fa-eye password-toggle" onclick="togglePassword('confirm_password')" style="position:absolute; right:10px; top:38px; cursor:pointer;"></i>
                                        @error('confirm_password')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>

                                </div>

                                <div class="col-md-12 col-sm-12 text-center">
                                    <button type="submit" class="btn btn-sm btn-success">Save &amp; Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    function togglePassword(fieldId) {
        var field = document.getElementById(fieldId);
        var icon = field.nextElementSibling; // get the eye icon
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    function validatePassword() {
        var newPassword = document.getElementById("new_password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        if (newPassword !== confirmPassword) {
            alert("Passwords do not match"); // simple alert
            return false;
        }
        return true;
    }
</script>
@endsection
