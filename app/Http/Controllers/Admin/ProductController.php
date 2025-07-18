<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = \App\Models\Product::latest()->paginate(10);
        return view('admin.products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Step 1: Validate the input data from the form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0', //Since column type was decimal, we will use numeric.
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', //The photo must be an image.
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            //Step 2: Upload the image to the storage/app/public/products folder
            //and get its path to save in the database
            $imagePath = $request->file('image')->store('products', 'public');
        }

        //Step 3: Save new product information in the products table
        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image' => $imagePath, //The column name 'image' is used according to table.
        ]);

        //Finally, we return the user to the same form page with a success message.
        return redirect()->route('admin.products.index')->with('success', __('Product added successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Simply return the edit view with the product data
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Step 1: Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is optional on update
        ]);

        // Step 2: Handle the image upload if a new one is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Store the new image and update the path in the validated data array
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Step 3: Update the product with the validated data
        $product->update($validated);

        // Step 4: Redirect back to the product list with a success message
        return redirect()->route('admin.products.index')->with('success', __('Product updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product) // <-- Change string $id to Product $product
    {
        // Step 1: Delete the product image from storage if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Step 2: Delete the product record from the database
        $product->delete();

        // Step 3: Redirect back to the product list with a success message
        return redirect()->route('admin.products.index')->with('success', __('Product deleted successfully.'));
    }
}
