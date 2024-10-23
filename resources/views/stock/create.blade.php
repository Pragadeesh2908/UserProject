@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Stock</h2>
    @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
    @endif
    <form action="{{ route('stock.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Stock Name</label>
            <input minlength="3" type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input min="0" type="number" name="quantity" class="form-control" id="quantity" required>
        </div>
        <div class="mb-3">
            <input type="checkbox" name="status" id="status" value="1" {{ old('status') ? 'checked' : '' }}>
            <label for="status">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Add Stock</button>
        <a href="{{route('stock.index')}}" class="btn btn-danger">cancle</a>
    </form>
</div>
@endsection