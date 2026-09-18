<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $query = Product::with('category')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        $selectedCategory = $request->filled('category_id') ? Category::find($request->category_id) : null;

        return view('welcome', compact('products', 'categories', 'selectedCategory'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('query');
        $categories = Category::withCount('products')->get();

        $query = Product::with('category')->where('name', 'LIKE', "%{$keyword}%");

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();

        return view('welcome', compact('products', 'categories', 'keyword'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}