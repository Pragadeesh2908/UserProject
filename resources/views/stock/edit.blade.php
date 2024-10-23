@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Edit Stock</h2>
    @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
    @endif
    <form action="{{ route('stock.update', $stock->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Stock Name</label>
            <input minlength="3" type="text" name="name" class="form-control" id="name" value="{{ old('name', $stock->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input min="0" type="number" name="quantity" class="form-control" id="quantity" value="{{ old('quantity', $stock->quantity) }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label><br>
            <input type="checkbox" name="status" id="status" value="1" {{ old('status', $stock->status) ? 'checked' : '' }}>
            <label for="status">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Update Stock</button>
        <a href="{{route('stock.index')}}" class="btn btn-danger">cancle</a>
    </form>
</div>
@endsection
