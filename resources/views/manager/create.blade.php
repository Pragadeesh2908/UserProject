@extends('layouts.app')

@section('title', 'Create Manager')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="container mt-4">
    <h2>Create New Manager</h2>

    <form action="{{ route('manager.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name:</label>
            <input type="text" minlength="3" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name:</label>
            <input type="text" minlength="1" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth:</label>
            <input type="date" class="form-control" name="dob" max="{{ date('Y-m-d', strtotime('-18 years')) }}" value="{{ old('dob') }}" required>
        </div>

        <div class="mb-3">
            <label for="stocks" class="form-label">Assign Stocks:</label>
            <div id="stockSelection">
                @if(old('stock_id'))
                    @foreach(old('stock_id') as $index => $stockId)
                        <div class="input-group mb-2 stock-group">
                            <select name="stock_id[]" class="form-select stock-select" required>
                                <option value="">Select Stock</option>
                                @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}" data-quantity="{{ $stock->quantity }}" {{ $stock->id == $stockId ? 'selected' : '' }}>{{ $stock->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="quantity[]" class="form-control quantity-input" min="1" style="width: 100px; margin-left: 5px;" placeholder="Qty" value="{{ old('quantity')[$index] }}" required>
                            <button type="button" class="btn btn-danger btn-sm close-stock-button" style="display: none;">X</button><br>
                            <span class="stock-quantity ms-2" style="font-weight: bold; display: none;"></span>
                            <span class="error-message text-danger" style="display: none; margin-left: 5px;"></span>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2 stock-group">
                        <select name="stock_id[]" class="form-select stock-select" required>
                            <option value="">Select Stock</option>
                            @foreach($stocks as $stock)
                            <option value="{{ $stock->id }}" data-quantity="{{ $stock->quantity }}">{{ $stock->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantity[]" class="form-control quantity-input" min="1" style="width: 100px; margin-left: 5px;" placeholder="Qty" required>
                        <button type="button" class="btn btn-danger btn-sm close-stock-button" style="display: none;">X</button><br>
                        <span class="stock-quantity ms-2" style="font-weight: bold; display: none;"></span>
                        <span class="error-message text-danger" style="display: none; margin-left: 5px;"></span>
                    </div>
                @endif
            </div>
            <button type="button" id="addStockButton" class="btn btn-primary btn-sm">Add Another Stock</button>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">User:</label>
            <select name="user_id[]" id="userDropdown" data-live-search="true" class="selectpicker" multiple required>
                @foreach($users as $user)
                @if($user->role == 1)
                <option value="{{ $user->id }}" {{ in_array($user->id, old('user_id', [])) ? 'selected' : '' }}>{{ $user->first_name }}</option>
                @endif
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-success">Create Manager</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        function updateQuantityDisplay(select) {
            const $stockGroup = $(select).closest('.stock-group');
            const $quantityDisplay = $stockGroup.find('.quantity-value');
            const $quantityText = $stockGroup.find('.stock-quantity');
            const $quantityInput = $stockGroup.find('.quantity-input');
            const $errorMessage = $stockGroup.find('.error-message');

            if ($(select).val()) {
                const maxQuantity = $(select).find('option:selected').data('quantity');
                $quantityDisplay.text(maxQuantity);
                $quantityText.show();
                $quantityInput.attr('max', maxQuantity).val('');
                $errorMessage.hide();
            } else {
                $quantityText.hide();
                $quantityInput.val('').removeAttr('max');
                $errorMessage.hide();
            }
        }

        function getSelectedStockIds() {
            let selectedStocks = [];
            $('.stock-select').each(function() {
                const selectedValue = $(this).val();
                if (selectedValue) {
                    selectedStocks.push(selectedValue);
                }
            });
            return selectedStocks;
        }

        function updateStockDropdowns() {
            const selectedStocks = getSelectedStockIds();

            $('.stock-select').each(function() {
                const $select = $(this);
                $select.find('option').each(function() {
                    if (selectedStocks.includes($(this).val()) && !$(this).is(':selected')) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
        }

        $('.stock-select').change(function() {
            updateQuantityDisplay(this);
            updateStockDropdowns();
        });

        function validateQuantity(input) {
            const $stockGroup = $(input).closest('.stock-group');
            const maxQuantity = $stockGroup.find('.stock-select option:selected').data('quantity');
            const quantityValue = parseInt($(input).val(), 10);
            const $errorMessage = $stockGroup.find('.error-message');
            if(maxQuantity == 0){
                $errorMessage.text('please choose some other stock,the stock is empty').show();
            }            
            else if (quantityValue > maxQuantity) {
                $errorMessage.text('Exceeded maximum quantity of ' + maxQuantity).show();
            }           
            else {
                $errorMessage.hide();
            }
        }

        $(document).on('input', '.quantity-input', function() {
            validateQuantity(this);
        });

        $('#addStockButton').click(function() {
            const $lastStockSelect = $('.stock-group').last().find('.stock-select');

            if ($lastStockSelect.val() !== '') {
                const $newStockGroup = $('.stock-group').first().clone();

                $newStockGroup.find('.stock-select').val('');
                $newStockGroup.find('.quantity-value').text('');
                $newStockGroup.find('.stock-quantity').hide();
                $newStockGroup.find('.quantity-input').val('').removeAttr('max');
                $newStockGroup.find('.error-message').hide();

                $newStockGroup.find('.stock-select').change(function() {
                    updateQuantityDisplay(this);
                    updateStockDropdowns();
                });

                const $closeButton = $newStockGroup.find('.close-stock-button');
                $closeButton.show().off('click').on('click', function() {
                    $newStockGroup.remove();
                    updateStockDropdowns();
                });

                $('#stockSelection').append($newStockGroup);

                updateStockDropdowns();
            } else {
                alert('Please select a stock before adding another.');
            }
        });

        $('.close-stock-button').on('click', function() {
            $(this).closest('.stock-group').remove();
            updateStockDropdowns();
        });

        updateStockDropdowns();
    });
    $(document).ready(function() {
        $('#userDropdown').selectpicker();
    });
</script>
<style>
    select[multiple] option::before {
        content: '';
    }

    select[multiple] option:checked::before {
        content: '✓';
        color: green;
        font-weight: bold;
        margin-right: 5px;
    }
</style>

@endsection