@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container my-5">
    <h2>Create New User</h2>
    @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
    @endif
    <form action="{{ route('users.store') }}" method="POST" id="form_submit">
        @csrf

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input minlength="3" type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
            <small id="first_name_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input minlength="1" type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
            <small id="last_name_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control {{$errors->has('email')?'is-invalid':''}}" value="{{ old('email') }}" required>
            @if($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{$errors->first('email')}}</strong>
            </span>
            @endif
            <small id="email_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="{{ old('dob', optional(old('dob'))->format('Y-m-d')) }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}" class="form-control">
            <small id="dob_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
            <small id="phone_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <small id="password_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            <small id="confirm_password_error" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        $('#phone_number').on('keyup', function() {
            const phoneNumber = $(this).val();
            const phonePattern = /^[0-9]{10}$/;
            if (phoneNumber !== "" && !phonePattern.test(phoneNumber)) {
                $('#phone_error').text('Phone number must be 10 digits');
            } else {
                $('#phone_error').text('');
            }
        });

        $('#password').on('keyup', function() {
            const password = $(this).val();

            const capitalLetterPattern = /[A-Z]/;
            const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;

            if (password.length < 8) {
                $('#password_error').text('Password must be at least 8 characters long');
            } else if (!capitalLetterPattern.test(password)) {
                $('#password_error').text('Password must contain at least 1 capital letter');
            } else if (!specialCharPattern.test(password)) {
                $('#password_error').text('Password must contain at least 1 special character');
            } else {
                $('#password_error').text('');
            }
        });

        $('#confirm_password').on('keyup', function() {
            const confirmPassword = $(this).val();
            const password = $('#password').val();
            if (confirmPassword !== password) {
                $('#confirm_password_error').text('Passwords do not match');
            } else {
                $('#confirm_password_error').text('');
            }
        });

        $('#form_submit').on('submit', function(event) {
            $('#first_name').trigger('keyup');
            $('#last_name').trigger('keyup');
            $('#email').trigger('keyup');
            $('#dob').trigger('keyup');
            $('#phone_number').trigger('keyup');
            $('#password').trigger('keyup');
            $('#confirm_password').trigger('keyup');

            if ($('.text-danger:contains("must")').length > 0) {
                event.preventDefault();
            }
        });
    });
</script>

@endsection