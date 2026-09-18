<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\Category;
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index() {
<<<<<<< HEAD
        $products = Product::with('category')->latest()->paginate(10);
=======
        $products = Product::latest()->paginate(10);
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
        return view('admin.products.index', compact('products'));
    }

    // Hiển thị form thêm sản phẩm
    public function create() {
<<<<<<< HEAD
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
=======
        return view('admin.products.create');
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }

    // Xử lý lưu sản phẩm mới
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
<<<<<<< HEAD
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
=======
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
<<<<<<< HEAD
            'category_id' => $request->category_id,
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // Hiển thị form sửa sản phẩm
    public function edit($id) {
        $product = Product::findOrFail($id);
<<<<<<< HEAD
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
=======
        return view('admin.products.edit', compact('product'));
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }

    // Xử lý cập nhật sản phẩm
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
<<<<<<< HEAD
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
=======
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
<<<<<<< HEAD
            'category_id' => $request->category_id,
=======
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // Xóa sản phẩm
    public function destroy($id) {
        $product = Product::findOrFail($id);

        $product->orderItems()->delete();

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}