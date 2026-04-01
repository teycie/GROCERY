<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // Keeping categories in one place makes forms and validation easy.
    private $categories = [
        'Frozen',
        'Beverage',
        'Snacks',
        'Fruits & Vegetables',
        'Pet Care',
        'Household Cleaning & Essentials',
    ];

    /**
     * Buyer product list with optional category filter.
     */
    public function index(Request $request)
    {
        $query = Product::with('images')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->paginate(8);
        $categories = $this->categories;

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function sellerIndex()
    {
        $products = Auth::user()->products()->latest()->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = $this->categories;

        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $validated['user_id'] = Auth::id();

        $product = Product::create($validated);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProductOwner($product);

        $categories = $this->categories;

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProductOwner($product);

        $validated = $this->validateProduct($request);

        $product->update($validated);

        // Handle multiple images - append to existing images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProductOwner($product);

        // Delete all associated images
        foreach ($product->images as $image) {
            if (File::exists('storage/' . $image->image_path)) {
                File::delete('storage/' . $image->image_path);
            }
            $image->delete();
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully.');
    }

    private function validateProduct(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|in:' . implode(',', $this->categories),
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);
    }

    private function authorizeProductOwner(Product $product)
    {
        if ((int) $product->user_id !== (int) Auth::id()) {
            abort(403, 'You can only manage your own products.');
        }
    }
}
