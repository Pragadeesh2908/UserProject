@extends('layouts.app')

@section('title', 'User List')

@section('content')
    <h2 class="mb-4 text-center">User List</h2>
    @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
    @endif
    <a href="{{ route('users.create') }}" class="btn btn-success float-end">Create New User</a>
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ route('export.users') }}" class="btn btn-success">Export Users to Excel</a>
    </div>
    <table class="table table-bordered" id="userTable">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user as $user)
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->phone_number }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script src="{{ asset('js/jquery-3.js') }}"></script>
    <script src="{{ asset('js/bootstrap5.js') }}"></script>
    <script src="{{ asset('js/datatable_bootstrap5.js') }}"></script>
    <script src="{{ asset('js/dataTables.js') }}"></script>
    <script>
        new DataTable('#userTable');
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user? This action cannot be undone.');
        }
    </script>
@endsection
