@extends('layouts.app')

@section('title', 'Managers List')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Managers List</h2>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-3">
                <a href="{{ route('manager.create') }}" class="btn btn-success">Create New Manager</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-white">
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Date of Birth</th>
                        <th>Stock</th>
                        <th>User</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($managers as $manager)
                        <tr>
                            <td>{{ $manager->id }}</td>
                            <td>{{ $manager->first_name }}</td>
                            <td>{{ $manager->last_name }}</td>
                            <td>{{ $manager->email }}</td>
                            <td>{{ $manager->dob }}</td>
                            <td>
                                @foreach($manager->stocks as $stock)
                                    {{ $stock->name }}<br>
                                @endforeach
                            </td>
                            <td>  @foreach($manager->Users as $user)
                                    {{ $user->first_name}}<br>
                                @endforeach</td>
                            <td>
                                <a href="{{ route('manager.edit', $manager->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('manager.destroy', $manager->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this manager?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- <script src="{{ asset('js/bootstrap4.js')}}"></script> -->
    <script src="{{ asset('js/jquery-3.js') }}"></script>
    <script src="{{ asset('js/bootstrap5.js') }}"></script>
@endsection
