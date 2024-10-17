@extends('layouts.app')

@section('title', 'Update Password')

@section('content')
<div class="container my-5">
    <h2>Update Password</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" id="updatePasswordForm">
        @csrf

        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="form-control" required>
            @error('current_password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" id="new_password" name="new_password" class="form-control" required>
            @error('new_password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <small id="newPasswordError" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
            <small id="confirmPasswordError" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-primary">Update Password</button>
        <a href="{{ route('profile') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#new_password').on('keyup', function() {
        validateNewPassword();
    });

    $('#new_password_confirmation').on('keyup', function() {
        validatePasswordConfirmation();
    });

    $('#updatePasswordForm').on('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

    function validateNewPassword() {
        const newPassword = $('#new_password').val();
        $('#newPasswordError').text('');

        const passwordRequirements = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
        if (!passwordRequirements.test(newPassword)) {
            $('#newPasswordError').text("New password must be at least 8 characters long, contain at least one uppercase letter and one special character.");
        }
    }

    function validatePasswordConfirmation() {
        const newPassword = $('#new_password').val();
        const newPasswordConfirm = $('#new_password_confirmation').val();
        $('#confirmPasswordError').text('');

        if (newPassword !== newPasswordConfirm) {
            $('#confirmPasswordError').text("New password and confirmation do not match.");
        }
    }

    function validateForm() {
        validateNewPassword();
        validatePasswordConfirmation();

        return !$('#newPasswordError').text() && !$('#confirmPasswordError').text();
    }
});
</script>
@endsection
