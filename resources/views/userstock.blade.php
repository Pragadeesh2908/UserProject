@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>User Stock Information</h2>
    
    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Stock ID</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user_stock as $stock)
            <tr>
                <td>{{ $stock->id }}</td>
                <td>{{ $stock->first_name }}</td>
                <td>{{ $stock->stock_name }}</td>
                <td>{{ $stock->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
