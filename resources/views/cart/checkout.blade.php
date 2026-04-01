@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<h1>Checkout</h1>

<div class="card checkout-page-card">
    <h2>Selected Items</h2>
    <div class="cart-list">
        @foreach($items as $item)
            <div class="cart-market-row">
                <div class="cart-market-col cart-market-item cart-market-item-wide">
                    @if(!empty($item['image_path']))
                        <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['product']->name }}" class="cart-item-image">
                    @endif
                    <div>
                        <strong>{{ $item['product']->name }}</strong>
                        <p class="small-text">Qty: {{ $item['quantity'] }}</p>
                    </div>
                </div>
                <div class="cart-market-col">
                    <label for="qty_{{ $item['product_id'] }}" class="small-text"><strong>Order Qty</strong></label>
                    <input
                        type="number"
                        id="qty_{{ $item['product_id'] }}"
                        name="quantities[{{ $item['product_id'] }}]"
                        min="1"
                        max="{{ $item['quantity'] }}"
                        value="{{ old('quantities.' . $item['product_id'], $item['quantity']) }}"
                        required
                    >
                    <p class="small-text">Max: {{ $item['quantity'] }}</p>
                </div>
                <div class="cart-market-col cart-market-subtotal">₱{{ number_format($item['line_total'], 2) }}</div>
            </div>
        @endforeach
    </div>

    <p class="checkout-total"><strong>Total:</strong> ₱{{ number_format($total, 2) }}</p>

    <form action="{{ route('cart.checkout') }}" method="POST" class="form-grid" id="placeOrderForm">
        @csrf

        @foreach($items as $item)
            <input type="hidden" name="selected_items[]" value="{{ $item['product_id'] }}">
        @endforeach

        <div class="checkout-form-grid">
            <div>
                <label for="first_name"><strong>First Name</strong></label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
            </div>

            <div>
                <label for="last_name"><strong>Last Name</strong></label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
            </div>
        </div>

        <div>
            <label for="address"><strong>Address</strong></label>
            <textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
        </div>

        <div>
            <label for="fulfillment_type"><strong>Fulfillment</strong></label>
            <select id="fulfillment_type" name="fulfillment_type" required>
                <option value="">Select fulfillment type</option>
                <option value="delivery" {{ old('fulfillment_type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
                <option value="pickup" {{ old('fulfillment_type') === 'pickup' ? 'selected' : '' }}>Pick-up</option>
            </select>
        </div>

        <div>
            <label for="payment_mode"><strong>Payment Mode</strong></label>
            <select id="payment_mode" name="payment_mode" required>
                <option value="">Select payment mode</option>
                <option value="cod" {{ old('payment_mode') === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                <option value="cash" {{ old('payment_mode') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="ewallet" {{ old('payment_mode') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            <p class="small-text">Delivery allows COD only. Pick-up allows Cash or E-Wallet.</p>
        </div>

        <div class="action-row">
            <a href="{{ route('cart.index') }}" class="btn btn-light">Back to Cart</a>
            <button type="submit" class="btn">Place Order</button>
        </div>
    </form>
</div>

<script>
    (function () {
        const fulfillment = document.getElementById('fulfillment_type');
        const payment = document.getElementById('payment_mode');

        if (!fulfillment || !payment) {
            return;
        }

        function syncPaymentOption() {
            const selected = fulfillment.value;
            const codOption = payment.querySelector('option[value="cod"]');
            const cashOption = payment.querySelector('option[value="cash"]');
            const ewalletOption = payment.querySelector('option[value="ewallet"]');

            if (codOption) {
                codOption.disabled = false;
            }

            if (cashOption) {
                cashOption.disabled = false;
            }

            if (ewalletOption) {
                ewalletOption.disabled = false;
            }

            if (selected === 'delivery') {
                payment.value = 'cod';
                if (cashOption) {
                    cashOption.disabled = true;
                }
                if (ewalletOption) {
                    ewalletOption.disabled = true;
                }
                return;
            }

            if (selected === 'pickup') {
                if (codOption) {
                    codOption.disabled = true;
                }

                if (!payment.value || payment.value === 'cod') {
                    payment.value = 'cash';
                }
                return;
            }

            payment.value = '';
        }

        fulfillment.addEventListener('change', syncPaymentOption);
        syncPaymentOption();
    })();
</script>
@endsection
