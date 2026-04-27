<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Show cart items stored in session.
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        $productIds = array_keys($cart);
        $products = Product::with('images')->whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            if (!isset($products[$productId])) {
                continue;
            }

            $product = $products[$productId];
            $lineTotal = $product->price * $quantity;
            $total += $lineTotal;

            $items[] = [
                'product_id' => $product->id,
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
                'image_path' => optional($product->images->first())->image_path,
            ];
        }

        $recentTrackings = Delivery::where('user_id', Auth::id())
            ->with(['product', 'seller'])
            ->latest()
            ->take(10)
            ->get();

        return view('cart.index', compact('items', 'total', 'recentTrackings'));
    }

    public function add(Product $product)
    {
        $quantity = request()->input('quantity', 1);
        $buyNow = request()->boolean('buy_now', false);
        $quantity = (int) $quantity;

        // Validate quantity
        if ($quantity <= 0) {
            return back()->withErrors(['quantity' => 'Quantity must be at least 1.']);
        }

        if ($quantity > $product->stock) {
            return back()->withErrors(['quantity' => 'Only ' . $product->stock . ' items in stock.']);
        }

        $cart = session()->get('cart', []);

        $currentQty = $cart[$product->id] ?? 0;
        $cart[$product->id] = $currentQty + $quantity;

        session()->put('cart', $cart);

        if ($buyNow) {
            return redirect()->route('cart.checkout.page', ['selected_items' => [$product->id]]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        unset($cart[$product->id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Product removed from cart.');
    }

    public function removeSelected(Request $request)
    {
        $validated = $request->validate([
            'selected_items' => ['required', 'array', 'min:1'],
            'selected_items.*' => ['integer'],
        ]);

        $cart = session()->get('cart', []);

        $selectedItems = collect($validated['selected_items'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        foreach ($selectedItems as $productId) {
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);

        $count = count($selectedItems);
        $message = $count === 1 ? '1 product removed from cart.' : $count . ' products removed from cart.';

        return back()->with('success', $message);
    }

    public function showCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors([
                'cart' => 'Your cart is empty. Add products before checkout.',
            ]);
        }

        $selectedItems = collect((array) $request->query('selected_items', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->withErrors([
                'selected_items' => 'Please select at least one item before checkout.',
            ]);
        }

        foreach ($selectedItems as $productId) {
            if (!array_key_exists($productId, $cart)) {
                return redirect()->route('cart.index')->withErrors([
                    'selected_items' => 'Please select only items that exist in your cart.',
                ]);
            }
        }

        $products = Product::with('images')->whereIn('id', $selectedItems)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($selectedItems as $productId) {
            $quantity = $cart[$productId] ?? 0;
            $product = $products[$productId] ?? null;

            if (!$product || $quantity <= 0) {
                continue;
            }

            $lineTotal = $product->price * $quantity;
            $total += $lineTotal;

            $items[] = [
                'product_id' => $product->id,
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
                'image_path' => optional($product->images->first())->image_path,
            ];
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->withErrors([
                'selected_items' => 'Selected items are no longer available. Please try again.',
            ]);
        }

        return view('cart.checkout', compact('items', 'total'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'fulfillment_type' => ['required', 'in:delivery,pickup'],
            'payment_mode' => ['required', 'in:cod,cash,ewallet'],
            'selected_items' => ['required', 'array', 'min:1'],
            'selected_items.*' => ['integer'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['fulfillment_type'] === 'delivery' && $validated['payment_mode'] !== 'cod') {
            throw ValidationException::withMessages([
                'payment_mode' => 'Delivery allows Cash on Delivery (COD) only.',
            ]);
        }

        if ($validated['fulfillment_type'] === 'pickup' && !in_array($validated['payment_mode'], ['cash', 'ewallet'], true)) {
            throw ValidationException::withMessages([
                'payment_mode' => 'Pick-up allows Cash or E-Wallet only.',
            ]);
        }

        $effectivePaymentMode = $validated['payment_mode'];

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty. Add products before checking out.',
            ]);
        }

        $selectedItems = collect($validated['selected_items'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        foreach ($selectedItems as $productId) {
            if (!array_key_exists($productId, $cart)) {
                throw ValidationException::withMessages([
                    'selected_items' => 'Please select only items that exist in your cart.',
                ]);
            }
        }

        $selectedCart = [];
        foreach ($selectedItems as $productId) {
            $requestedQuantity = isset($validated['quantities'][$productId])
                ? (int) $validated['quantities'][$productId]
                : 0;

            if ($requestedQuantity <= 0) {
                throw ValidationException::withMessages([
                    'quantities.' . $productId => 'Please enter a valid quantity.',
                ]);
            }

            if ($requestedQuantity > $cart[$productId]) {
                throw ValidationException::withMessages([
                    'quantities.' . $productId => 'Quantity cannot be more than what is in your cart.',
                ]);
            }

            $selectedCart[$productId] = $requestedQuantity;
        }

        DB::transaction(function () use ($selectedCart, $validated, $effectivePaymentMode) {
            $products = Product::whereIn('id', array_keys($selectedCart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $orderReference = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));
            $lineNumber = 1;

            foreach ($selectedCart as $productId => $quantity) {
                $product = $products[$productId] ?? null;

                if (!$product) {
                    throw ValidationException::withMessages([
                        'cart' => 'One or more cart items are no longer available.',
                    ]);
                }

                if ($quantity <= 0) {
                    throw ValidationException::withMessages([
                        'cart' => 'Invalid cart quantity detected. Please update your cart.',
                    ]);
                }

                if ($quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => 'Insufficient stock for ' . $product->name . '. Available: ' . $product->stock . '.',
                    ]);
                }

                if (!$product->user_id) {
                    throw ValidationException::withMessages([
                        'cart' => 'Seller account is missing for ' . $product->name . '.',
                    ]);
                }

                $fulfillmentType = $validated['fulfillment_type'];
                $paymentMode = $effectivePaymentMode;
                $notes = 'Checkout by ' . $validated['first_name'] . ' ' . $validated['last_name']
                    . ' | Fulfillment: ' . ucfirst($fulfillmentType)
                    . ' | Payment: ' . strtoupper($paymentMode);

                Delivery::create([
                    'order_id' => $orderReference . '-' . $lineNumber,
                    'user_id' => Auth::id(),
                    'seller_id' => $product->user_id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'fulfillment_type' => $fulfillmentType,
                    'payment_mode' => $paymentMode,
                    'status' => 'pending',
                    'tracking_status' => 'pending',
                    'address' => $validated['address'],
                    'estimated_date' => $fulfillmentType === 'delivery' ? now()->addDays(3)->toDateString() : null,
                    'notes' => $notes,
                ]);

                $lineNumber++;

                $product->decrement('stock', $quantity);
            }
        });

        foreach ($selectedItems as $productId) {
            $cart[$productId] = (int) $cart[$productId] - (int) $selectedCart[$productId];

            if ($cart[$productId] <= 0) {
                unset($cart[$productId]);
            }
        }
        session()->put('cart', $cart);

        $fulfillmentLabel = $validated['fulfillment_type'] === 'delivery' ? 'Delivery' : 'Pick-up';
        if ($effectivePaymentMode === 'cod') {
            $paymentLabel = 'Cash on Delivery (COD)';
        } elseif ($effectivePaymentMode === 'cash') {
            $paymentLabel = 'Cash';
        } else {
            $paymentLabel = 'E-Wallet';
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Checkout successful for ' . $validated['first_name'] . ' ' . $validated['last_name'] . ' via ' . $fulfillmentLabel . ' using ' . $paymentLabel . '.');
    }
}
