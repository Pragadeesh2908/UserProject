@extends('layouts.app')

@section('title', 'Edit Manager')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<div class="container mt-4">
    <h2>Edit Manager: {{ $manager->first_name }} {{ $manager->last_name }}</h2>

    <form action="{{ route('manager.update', $manager->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name:</label>
            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $manager->first_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name:</label>
            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $manager->last_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email', $manager->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth:</label>
            <input type="date" class="form-control" name="dob" value="{{ old('dob', $manager->dob) }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
        </div>

        <div class="mb-3">
            <label for="stocks" class="form-label">Assign Stocks:</label>
            <div id="stockSelection">
                @foreach($manager->stocks as $assignedStock)
                <div class="input-group mb-2 stock-group">
                    <select name="stock_id[]" class="form-select stock-select" required>
                        <option value="">Select Stock</option>
                        @foreach($stocks as $stock)
                        <option value="{{ $stock->id }}" data-quantity="{{ $stock->quantity }}" {{ $assignedStock->id == $stock->id ? 'selected' : '' }}>
                            {{ $stock->name }}
                        </option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity[]" class="form-control quantity-input" min="1" max="{{ $assignedStock->pivot->quantity }}" value="{{ $assignedStock->pivot->quantity }}" style="width: 100px; margin-left: 5px;" placeholder="Qty" required>
                    <button type="button" class="btn btn-danger btn-sm close-stock-button" style="display: none;">X</button>
                    <span class="error-message text-danger" style="display: none; margin-left: 5px;"></span>
                </div>
                @endforeach
            </div>
            <button type="button" id="addStockButton" class="btn btn-primary btn-sm">Add Another Stock</button>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">User:</label>
            <select name="user_id[]" class="form-select" multiple required>
                <option value="">Select User</option>
                @foreach($availableUsers as $user)
                <option value="{{ $user->id }}" {{ in_array($user->id, $assignedUserIds) ? 'selected' : '' }}>
                    {{ $user->first_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" name="password_confirmation" placeholder="Leave blank to keep current password">
        </div>

        <button type="submit" class="btn btn-success">Update Manager</button>
        <a href="{{ route('manager.index') }}" class="btn btn-danger">Cancel</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

            if (quantityValue > maxQuantity) {
                $errorMessage.text('Exceeded maximum quantity of ' + maxQuantity).show();
            } else {
                $errorMessage.hide();
            }
        }

        $(document).on('input', '.quantity-input', function() {
            validateQuantity(this);
        });

        $('#addStockButton').click(function() {
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
        });

        $('.close-stock-button').on('click', function() {
            $(this).closest('.stock-group').remove();
            updateStockDropdowns();
        });
        updateStockDropdowns();
    });
</script>

@endsection