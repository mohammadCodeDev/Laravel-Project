<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
