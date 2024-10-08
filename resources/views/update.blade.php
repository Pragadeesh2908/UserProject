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

    <form action="{{ route('password.update') }}" method="POST" onsubmit="return validateForm()">
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
            <input type="password" id="new_password" name="new_password" class="form-control" required onkeyup="validateNewPassword()">
            @error('new_password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <small id="newPasswordError" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required onkeyup="validatePasswordConfirmation()">
            <small id="confirmPasswordError" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-primary">Update Password</button>
        <a href="{{ route('profile') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
function validateNewPassword() {
    const newPassword = document.getElementById('new_password').value;
    const newPasswordError = document.getElementById('newPasswordError');
    
    newPasswordError.textContent = '';

    const passwordRequirements = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
    if (!passwordRequirements.test(newPassword)) {
        newPasswordError.textContent = "New password must be at least 8 characters long, contain at least one uppercase letter and one special character.";
    }
}

function validatePasswordConfirmation() {
    const newPassword = document.getElementById('new_password').value;
    const newPasswordConfirm = document.getElementById('new_password_confirmation').value;
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    
    confirmPasswordError.textContent = '';

    if (newPassword !== newPasswordConfirm) {
        confirmPasswordError.textContent = "New password and confirmation do not match.";
    }
}

function validateForm() {
    validateNewPassword();
    validatePasswordConfirmation();

    return !document.getElementById('newPasswordError').textContent && !document.getElementById('confirmPasswordError').textContent;
}
</script>
@endsection
