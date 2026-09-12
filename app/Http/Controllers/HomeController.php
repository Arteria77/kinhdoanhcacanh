<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('welcome', compact('products'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$keyword}%")->latest()->get();

        return view('welcome', compact('products', 'keyword'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}