<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $products = Product::all();
        return view('products.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products',
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'opening_stock' => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'purchase_price' => $request->purchase_price,
            'sell_price' => $request->sell_price,
            'opening_stock' => $request->opening_stock,
            'current_stock' => $request->opening_stock,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // public function update(Request $request, Product $product)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'purchase_price' => 'required|numeric|min:0',
    //         'sell_price' => 'required|numeric|min:0',
    //     ]);

    //     $product->update($request->all());
    //     return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    // }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code,' . $product->id,
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gt:purchase_price',
            'stock_adjustment' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $product->update([
            'name' => $request->name,
            'code' => $request->code,
            'purchase_price' => $request->purchase_price,
            'sell_price' => $request->sell_price,
            'description' => $request->description,
        ]);

        if ($request->stock_adjustment) {
            $product->increment('current_stock', $request->stock_adjustment);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
