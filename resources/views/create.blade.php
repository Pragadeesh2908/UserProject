@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container my-5">
    <h2>Create New User</h2>
    
    <form action="{{ route('users.store') }}" method="POST" onsubmit="return validateForm()">
        @csrf

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" onkeyup="validateFirstName()" required>
            <small id="first_name_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" onkeyup="validateLastName()" required>
            <small id="last_name_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control {{$errors->has('email')?'is-invalid':''}}" value="{{old('email')}}" onkeyup="validateEmail()" required>
            @if($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{$errors->first('email')}}</strong>
            </span>
            @endif
            <small id="email_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" id="dob" name="dob" class="form-control" onkeyup="validateDOB()">
            <small id="dob_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control" onkeyup="validatePhoneNumber()">
            <small id="phone_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" onkeyup="validatePassword()" required>
            <small id="password_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" onkeyup="validateConfirmPassword()" required>
            <small id="confirm_password_error" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-primary" onclick="validateForm()">Create User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
    function validateFirstName() {
        const firstName = document.getElementById('first_name').value;
        const error = document.getElementById('first_name_error');
        if (firstName.length < 2) {
            error.textContent = 'First name must be at least 2 characters long';
            return false;
        } else {
            error.textContent = '';
            return true;
        }
    }

    function validateLastName() {
        const lastName = document.getElementById('last_name').value;
        const error = document.getElementById('last_name_error');
        if (lastName.length < 1) {
            error.textContent = 'Last name must be at least 2 characters long';
            return false;
        } else {
            error.textContent = '';
            return true;
        }
    }

    function validateEmail() {
        const email = document.getElementById('email').value;
        const error = document.getElementById('email_error');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            error.textContent = 'Invalid email format';
            return false;
        } else {
            error.textContent = '';
            return true;
        }
    }


    function setDefaultDOB() {
        const dobInput = document.getElementById('dob');
        const today = new Date();
        const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

        const formattedDate = eighteenYearsAgo.toISOString().split('T')[0];
        dobInput.value = formattedDate;
        dobInput.max = formattedDate;
    }

    window.onload = setDefaultDOB;

    function validateDOB() {
        const dobInput = document.getElementById('dob').value;
        const error = document.getElementById('dob_error');

        if (dobInput) {
            const dob = new Date(dobInput);
            const today = new Date();

            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            const dayDiff = today.getDate() - dob.getDate();


            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                age--;
            }


            if (age < 18) {
                error.textContent = 'You must be at least 18 years old';
                return false;
            } else {
                error.textContent = '';
                return true;
            }
        }
        return true;
    }

    function validatePhoneNumber() {
        const phone = document.getElementById('phone_number').value;
        const error = document.getElementById('phone_error');
        const phonePattern = /^\d{10}$/;
        if (!phonePattern.test(phone)) {
            error.textContent = 'Phone number must be 10 digits long';
            return false;
        } else {
            error.textContent = '';
            return true;
        }
    }

    function validatePassword() {
        const password = document.getElementById('password').value;
        const error = document.getElementById('password_error');
        const passwordPattern = /^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[a-zA-Z0-9]).{8,}$/;

        if (!passwordPattern.test(password)) {
            error.textContent = 'Password must be at least 8 characters long, contain at least 1 capital letter and 1 special character';
            return false;
        } else {
            error.textContent = '';
            return true;
        }
    }

    function validateConfirmPassword() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const error = document.getElementById('confirm_password_error');
        if (password !== confirmPassword) {
            error.textContent = 'Passwords do not match';
            return false;
        } else {
            error.textContent = '';
            return true;
        }
    }

    function validateForm() {
        return validateFirstName() &&
            validateLastName() &&
            validateEmail() &&
            validateDOB() &&
            validatePhoneNumber() &&
            validatePassword() &&
            validateConfirmPassword();
    }
</script>
@endsection