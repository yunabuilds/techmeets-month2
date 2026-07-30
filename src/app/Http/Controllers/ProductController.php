<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:200',
            'price' => 'required|integer',
            'description' => 'required',
            'stock' => 'required|integer',
            'category' => 'required',
        ]);

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)->with('success', '商品を作成しました');
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:200',
            'price' => 'required|integer',
            'description' => 'required',
            'stock' => 'required|integer',
            'category' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validated);

        return redirect()->route('products.show', $product)->with('success', '商品を更新しました');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました');
    }
}