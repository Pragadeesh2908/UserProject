@extends('layouts.app')

@section('content')

<div class="container my-5">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST" id="editUserForm" onsubmit="return confirm('Are you sure you want to update this user?')">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->first_name }}" required>
            <small id="firstNameError" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $user->last_name }}" required>
            <small id="lastNameError" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control {{$errors->has('email')?'is-invalid':''}}" value="{{ $user->email }}" required>
            <small id="emailError" class="text-danger"></small>
            @if($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{$errors->first('email')}}</strong>
            </span>
            @endif
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" id="dob" name="dob" class="form-control" value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}">
            <small id="dob_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ $user->phone_number }}">
            <small id="phoneNumberError" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
   
    $('#phone_number').on('keyup', validatePhoneNumber);
    
    function validatePhoneNumber() {
        const phoneNumber = $('#phone_number').val();
        const regex = /^\d{10}$/;
        if (phoneNumber && !regex.test(phoneNumber)) {
            $('#phoneNumberError').text("Phone number must be between 10 and 15 digits.");
        } else {
            $('#phoneNumberError').text("");
        }
    }
});
</script>
@endsection
