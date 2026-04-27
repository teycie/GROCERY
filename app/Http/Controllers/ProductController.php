<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $search = trim((string) $request->input('search', ''));

        $query = Product::with(['images', 'user'])
            ->whereHas('user', function ($userQuery) {
                $userQuery->whereIn('role', ['seller', 'admin']);
            })
            ->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(8)->withQueryString();
        $categories = $this->categories;

        return view('products.index', compact('products', 'categories', 'search'));
    }

    public function show(Product $product)
    {
        $product->load(['images', 'user']);

        if (!in_array(optional($product->user)->role, ['seller', 'admin'])) {
            abort(404);
        }

        return view('products.show', compact('product'));
    }

    public function sellerIndex(Request $request)
    {
        $query = Product::with(['images', 'user'])
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['seller', 'admin']);
            })
            ->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = $this->categories;

        $categoryCounts = Product::whereHas('user', function ($query) {
                $query->whereIn('role', ['seller', 'admin']);
            })
            ->select('category', \DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $selectedCategory = $request->category;

        return view('seller.products.index', compact('products', 'categories', 'categoryCounts', 'selectedCategory'));
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
        $product = $this->findManageableProductOrFail($product);

        $categories = $this->categories;

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product = $this->findManageableProductOrFail($product);
        $product->load('images');

        $validated = $this->validateProduct($request);

        $request->validate([
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
            'replace_images' => 'nullable|array',
            'replace_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product->update($validated);

        $deleteImageIds = collect($request->input('delete_images', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($deleteImageIds->isNotEmpty()) {
            $imagesToDelete = $product->images()
                ->whereIn('id', $deleteImageIds)
                ->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        foreach ($product->images as $image) {
            if ($deleteImageIds->contains($image->id)) {
                continue;
            }

            if ($request->hasFile('replace_images.' . $image->id)) {
                Storage::disk('public')->delete($image->image_path);

                $newImagePath = $request->file('replace_images')[$image->id]->store('products', 'public');
                $image->update([
                    'image_path' => $newImagePath,
                ]);
            }
        }

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
        $product = $this->findManageableProductOrFail($product);
        $product->load('images');

        // Delete all associated images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
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

    private function findManageableProductOrFail(Product $product): Product
    {
        return Product::whereKey($product->id)
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['seller', 'admin']);
            })
            ->firstOrFail();
    }
}
