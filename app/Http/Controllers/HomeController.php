<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
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
=======
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('welcome', compact('products'));
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }

    public function search(Request $request)
    {
        $keyword = $request->input('query');
<<<<<<< HEAD
        $categories = Category::withCount('products')->get();

        $query = Product::with('category')->where('name', 'LIKE', "%{$keyword}%");

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();

        return view('welcome', compact('products', 'categories', 'keyword'));
=======
        $products = Product::where('name', 'LIKE', "%{$keyword}%")->latest()->get();

        return view('welcome', compact('products', 'keyword'));
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }

    public function show($id)
    {
<<<<<<< HEAD
        $product = Product::with('category')->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
=======
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }
}