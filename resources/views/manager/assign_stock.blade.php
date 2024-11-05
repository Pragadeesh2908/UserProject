@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Assign Stock to User</h2>

    <form action="{{ route('manager.assignStockToUser') }}" method="POST" id="assignStockForm">
        @csrf
        <input type="hidden" name="manager_id" value="{{ $managerId }}">

        <div class="form-group mb-3">
            <label for="user">Select User:</label>
            <select name="user_id" class="form-control" required>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="stock">Select Stock:</label>
            <select name="stock_id" class="form-control" id="stockSelect" required>
                @foreach ($stocks as $stock)
                <option value="{{ $stock->stock_id }}" data-quantity="{{ $stock->quantity }}">
                    {{ $stock->name }} (Available: {{ $stock->quantity }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" class="form-control" min="1" id="quantityInput" required>
            <p id="errorMessage" style="color: red; display: none;"></p> 
        </div>

        <button type="submit" class="btn btn-primary">Assign Stock</button>
    </form>
</div>

<script>
    document.getElementById('assignStockForm').addEventListener('submit', function(event) {
        const stockSelect = document.getElementById('stockSelect');
        const quantityInput = document.getElementById('quantityInput');
        const selectedOption = stockSelect.options[stockSelect.selectedIndex];
        const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity'), 10);
        const requestedQuantity = parseInt(quantityInput.value, 10);
        const errorMessage = document.getElementById('errorMessage');

        errorMessage.textContent = '';
        errorMessage.style.display = 'none';

        if (requestedQuantity > availableQuantity) {
            event.preventDefault(); 
            errorMessage.textContent = `You have only ${availableQuantity} units of this stock.`;
            errorMessage.style.display = 'block'; 
        }
    });
</script>
@endsection
