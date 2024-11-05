@extends('layouts.app')

@section('title', 'Manager Stocks')

@section('content')
<div class="container">
    <h1 class="my-4">Manager Stocks</h1>

    @auth
    <p class="lead">Hello, <b style="font-weight: 600;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</b> !</p>
    <!-- @if($stock && $stock->isNotEmpty())
    <h2 class="my-4">Stocks Managed:</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Stock Name</th>
                    <th scope="col">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stock as $data)
                <tr>
                    <td>{{ $data->stock_name }}</td>
                    <td>{{ $data->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-warning" role="alert">
        No stocks found for this manager.
    </div>
    @endif
    @if($users && $users->isNotEmpty())
    <h2 class="my-4">Users Under This Manager:</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-warning" role="alert">
        No users found under this manager.
    </div>
    @endif -->
    @else
    <div class="alert alert-info" role="alert">
        Please log in to access the manager stocks.
    </div>
    @endauth
</div>
@endsection