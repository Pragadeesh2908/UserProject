<!-- resources/views/profile.blade.php -->
@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">User Profile</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>First Name</td>
                    <td>{{ $user->first_name }}</td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td>{{ $user->last_name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td>{{ $user->dob ? $user->dob->format('F j, Y') : 'Not provided' }}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td>{{ $user->phone_number }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4">
            <!-- <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Update</a> -->
            <!-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form> -->
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users List</a>
        </div>
    </div>
@endsection
