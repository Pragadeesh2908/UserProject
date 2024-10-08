@extends('layouts.app')

@section('content')

<div class="container my-5">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST" id="editUserForm" onsubmit=" return confirm('Are you sure you want to updated this user?')">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->first_name }}" onkeyup="validateFirstName()" required>
            <small id="firstNameError" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $user->last_name }}" onkeyup="validateLastName()" required>
            <small id="lastNameError" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control {{$errors->has('email')?'is-invalid':''}}" value="{{ $user->email }}" onkeyup="validateEmail()" required>
            <small id="emailError" class="text-danger"></small>
            @if($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{$errors->first('email')}}</strong>
            </span>
            @endif
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" id="dob" name="dob" class="form-control" value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}" onchange="validateDOB()">
            <small id="dob_error" class="text-danger"></small>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ $user->phone_number }}" onkeyup="validatePhoneNumber()">
            <small id="phoneNumberError" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    let user = <?php echo json_encode($user); ?>;
    console.log(user);

    function validateFirstName() {
        const firstName = document.getElementById('first_name').value;
        const error = document.getElementById('firstNameError');
        if (firstName.length < 2 || firstName.length > 50) {
            error.textContent = "First name must be between 2 and 50 characters.";
        } else {
            error.textContent = "";
        }
    }

    function validateLastName() {
        const lastName = document.getElementById('last_name').value;
        const error = document.getElementById('lastNameError');
        if (lastName.length < 1 || lastName.length > 50) {
            error.textContent = "Last name must be between 2 and 50 characters.";
        } else {
            error.textContent = "";
        }
    }

    function validateEmail() {
        const email = document.getElementById('email').value;
        const error = document.getElementById('emailError');
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(email)) {
            error.textContent = "Please enter a valid email address.";
        } else {
            error.textContent = "";
        }
    }
    
    function validateDOB() {
        const dobInput = document.getElementById('dob').value;
        console.log(dobInput);

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
        const phoneNumber = document.getElementById('phone_number').value;
        const error = document.getElementById('phoneNumberError');
        const regex = /^\d{10,15}$/;
        if (phoneNumber && !regex.test(phoneNumber)) {
            error.textContent = "Phone number must be between 10 and 15 digits.";
        } else {
            error.textContent = "";
        }
    }
</script>
@endsection
