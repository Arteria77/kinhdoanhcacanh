<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    // Hiển thị danh sách giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request, $id)
    {
        // 1. Bắt buộc đăng nhập trước khi thêm giỏ hàng
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập tài khoản để thêm sản phẩm vào giỏ hàng.');
        }

        // 2. Bắt buộc xác thực email trước khi thêm giỏ hàng
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with('error', 'Bạn cần xác thực địa chỉ email trước khi có thể thêm sản phẩm vào giỏ hàng.');
        }

        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        $quantity = (int) $request->input('quantity', 1);
        
        $currentCartQuantity = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        $totalRequested = $currentCartQuantity + $quantity;

        if ($totalRequested > $product->stock) {
            return redirect()->back()->with('error', 'Số lượng đặt mua vượt quá tồn kho hiện tại (Còn lại: ' . $product->stock . ').');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $totalRequested;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image ?? '',
                "stock" => $product->stock
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->route('cart.success');
    }

    // Trang thông báo thêm giỏ hàng thành công
    public function success()
    {
        return view('cart.success', ['order' => null]);
    }

    // Cập nhật số lượng sản phẩm trong giỏ
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart', []);
            if(isset($cart[$request->id])) {
                $product = Product::find($request->id);
                if($product && $request->quantity > $product->stock) {
                    return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho cho phép (' . $product->stock . ').');
                }
                
                $cart[$request->id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
            
            return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
        }
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart', []);
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            
            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }
    }

    // Hàm tính phí vận chuyển qua API Giao Hàng Nhanh (GHN Sandbox)
    public function calculateShippingFee(Request $request)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
        ]);

        $payload = [
            'service_type_id' => 2,
            'to_district_id' => (int) $request->to_district_id,
            'weight' => (int) ($request->weight ?? 500),
        ];

        if ($request->filled('to_ward_code')) {
            $payload['to_ward_code'] = (string) $request->to_ward_code;
        }

        try {
            $token = config('services.ghn.token', 'eea1eb4a-aa85-11f1-a973-aee5264794df');
            $shopId = (int) config('services.ghn.shop_id', 217505);
            $apiUrl = rtrim(config('services.ghn.api_url', 'https://dev-online-gateway.ghn.vn/shiip/public-api'), '/');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $token,
                'ShopId' => $shopId,
            ])->post($apiUrl . '/v2/shipping-order/fee', $payload);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['code']) && $data['code'] == 200) {
                    return response()->json([
                        'success' => true,
                        'fee' => $data['data']['total'] ?? 0
                    ]);
                }
            }

            $errorData = $response->json();
            return response()->json([
                'success' => false,
                'message' => $errorData['message_display'] ?? $errorData['message'] ?? 'Không thể tính phí vận chuyển lúc này.'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối GHN: ' . $e->getMessage()
            ], 500);
        }
    }

    // Lấy danh sách Tỉnh/Thành phố từ GHN Sandbox
    public function getProvinces()
    {
        try {
            $token = config('services.ghn.token', 'eea1eb4a-aa85-11f1-a973-aee5264794df');
            $apiUrl = rtrim(config('services.ghn.api_url', 'https://dev-online-gateway.ghn.vn/shiip/public-api'), '/');

            $response = Http::withHeaders([
                'Token' => $token
            ])->get($apiUrl . '/master-data/province');

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    // Lấy danh sách Quận/Huyện theo Tỉnh/Thành phố (Sandbox)
    public function getDistricts(Request $request)
    {
        try {
            $token = config('services.ghn.token', 'eea1eb4a-aa85-11f1-a973-aee5264794df');
            $apiUrl = rtrim(config('services.ghn.api_url', 'https://dev-online-gateway.ghn.vn/shiip/public-api'), '/');

            $response = Http::withHeaders([
                'Token' => $token
            ])->get($apiUrl . '/master-data/district', [
                'province_id' => (int) $request->province_id
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    // Lấy danh sách Phường/Xã theo Quận/Huyện (Sandbox)
    public function getWards(Request $request)
    {
        try {
            $token = config('services.ghn.token', 'eea1eb4a-aa85-11f1-a973-aee5264794df');
            $apiUrl = rtrim(config('services.ghn.api_url', 'https://dev-online-gateway.ghn.vn/shiip/public-api'), '/');

            $response = Http::withHeaders([
                'Token' => $token
            ])->get($apiUrl . '/master-data/ward', [
                'district_id' => (int) $request->district_id
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }
}