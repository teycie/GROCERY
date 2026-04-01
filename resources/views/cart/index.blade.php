@extends('layouts.app')

@section('title', 'My Cart')

@section('content')
<h1>My Cart</h1>

<div class="card">
    @php
        $selectedItemIds = old('selected_items', collect($items)->pluck('product_id')->all());
    @endphp

    @if(empty($items))
        <p>Your cart is empty.</p>
    @else
        <form action="{{ route('cart.checkout') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="cart-list">
                @foreach($items as $item)
                    @php
                        $isChecked = in_array($item['product_id'], $selectedItemIds);
                        $unitPrice = $item['quantity'] > 0 ? $item['line_total'] / $item['quantity'] : 0;
                    @endphp
                    <div class="cart-market-row">
                        <div class="cart-market-col cart-market-check">
                            <input
                                type="checkbox"
                                class="js-item-checkbox"
                                name="selected_items[]"
                                value="{{ $item['product_id'] }}"
                                data-quantity="{{ $item['quantity'] }}"
                                data-line-total="{{ $item['line_total'] }}"
                                {{ $isChecked ? 'checked' : '' }}
                            >
                        </div>

                        <div class="cart-market-col cart-market-item">
                            @if(!empty($item['image_path']))
                                <img src="{{ asset('storage/' . $item['image_path']) }}" alt="{{ $item['product']->name }}" class="cart-item-image">
                            @endif
                            <div>
                                <strong>{{ $item['product']->name }}</strong>
                                <p class="small-text">Qty: {{ $item['quantity'] }}</p>
                            </div>
                        </div>

                        <div class="cart-market-col cart-market-price">₱{{ number_format($unitPrice, 2) }}</div>
                        <div class="cart-market-col cart-market-subtotal">₱{{ number_format($item['line_total'], 2) }}</div>

                        <div class="cart-market-col cart-market-actions">
                            <button
                                class="btn-remove-link"
                                type="submit"
                                formaction="{{ route('cart.remove', $item['product']) }}"
                                formnovalidate
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-sticky-bar">
                <label class="select-all-row">
                    <input type="checkbox" id="selectAllItems">
                    <span>Select All</span>
                </label>

                <button
                    class="btn-delete-selected"
                    type="button"
                    id="deleteSelectedBtn"
                >
                    Delete
                </button>

                <div class="cart-sticky-summary">
                    <span>Total (<span id="selectedCount">0</span> item):</span>
                    <strong>₱<span id="selectedTotal">0.00</span></strong>
                </div>

                <button
                    class="btn cart-checkout-btn"
                    type="submit"
                    formmethod="GET"
                    formaction="{{ route('cart.checkout.page') }}"
                >
                    Checkout
                </button>
            </div>
        </form>
    @endif
</div>

<script>
    (function () {
        const checkoutForm = document.getElementById('checkoutForm');
        const selectAllItems = document.getElementById('selectAllItems');
        const selectedCount = document.getElementById('selectedCount');
        const selectedTotal = document.getElementById('selectedTotal');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');

        if (!checkoutForm || !selectAllItems || !selectedCount || !selectedTotal || !deleteSelectedBtn) {
            return;
        }

        const itemCheckboxes = Array.from(checkoutForm.querySelectorAll('.js-item-checkbox'));

        function getSelectedItemsCount() {
            return itemCheckboxes.filter((checkbox) => checkbox.checked).length;
        }

        function getSelectedItemsTotal() {
            let total = 0;

            itemCheckboxes.forEach(function (checkbox) {
                if (!checkbox.checked) {
                    return;
                }

                const lineTotal = Number(checkbox.dataset.lineTotal || 0);
                total += lineTotal;
            });

            return total;
        }

        function updateSelectionSummary() {
            const count = getSelectedItemsCount();
            const total = getSelectedItemsTotal();

            selectedCount.textContent = String(count);
            selectedTotal.textContent = total.toFixed(2);

            selectAllItems.checked = itemCheckboxes.length > 0 && count === itemCheckboxes.length;
            selectAllItems.indeterminate = count > 0 && count < itemCheckboxes.length;

            deleteSelectedBtn.disabled = count === 0;
        }

        selectAllItems.addEventListener('change', function () {
            itemCheckboxes.forEach(function (checkbox) {
                checkbox.checked = selectAllItems.checked;
            });

            updateSelectionSummary();
        });

        deleteSelectedBtn.addEventListener('click', function () {
            const selectedCount = getSelectedItemsCount();

            if (selectedCount === 0) {
                window.alert('Please select at least one item to delete.');
                return;
            }

            if (!window.confirm('Are you sure you want to delete ' + selectedCount + ' item(s)?')) {
                return;
            }

            const deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.action = '{{ route("cart.remove-selected") }}';
            deleteForm.style.display = 'none';

            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = '{{ csrf_token() }}';
            deleteForm.appendChild(csrfField);

            itemCheckboxes.forEach(function (checkbox) {
                if (!checkbox.checked) {
                    return;
                }

                const field = document.createElement('input');
                field.type = 'hidden';
                field.name = 'selected_items[]';
                field.value = checkbox.value;
                deleteForm.appendChild(field);
            });

            document.body.appendChild(deleteForm);
            deleteForm.submit();
        });

        itemCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateSelectionSummary);
        });

        updateSelectionSummary();
    })();
</script>
@endsection
