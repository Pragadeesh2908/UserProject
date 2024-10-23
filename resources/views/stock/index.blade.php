@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    <h2>Stock List</h2>
    <a href="{{ route('stock.create') }}" class="btn btn-success mb-3">Add New Stock</a>
    <table class="table table-bordered" id="stocktable">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
            @if($stock->quantity == 0)
                <p class="alert alert-danger">please refill <b>{{$stock->name}}</b> stock.The stock is empty</p>
            @endif
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $stock->name }}</td>
                <td>{{ $stock->quantity }}</td>
                <td>{{ $stock->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('stock.edit', $stock->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('stock.destroy', $stock->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script src="{{ asset('js/jquery-3.js') }}"></script>
<!-- <script src="{{ asset('js/datatable_bootstrap5.js') }}"></script> -->
<script src="{{ asset('js/dataTables.js') }}"></script>
<script>
    new DataTable('#stocktable');
</script>
@endsection