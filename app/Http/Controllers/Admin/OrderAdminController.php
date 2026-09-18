<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\Order;
use App\Services\GhnService;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    // Hiển thị danh sách tất cả đơn hàng cho admin (có lọc trạng thái, thanh toán, tìm kiếm và phân trang)
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])->latest();

        // 1. Tìm kiếm theo từ khóa (Mã đơn, Tên người nhận, SĐT, Mã vận đơn GHN)
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%{$keyword}%")
                  ->orWhere('shipping_name', 'like', "%{$keyword}%")
                  ->orWhere('shipping_phone', 'like', "%{$keyword}%")
                  ->orWhere('ghn_order_code', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($uq) use ($keyword) {
                      $uq->where('name', 'like', "%{$keyword}%")
                         ->orWhere('email', 'like', "%{$keyword}%")
                         ->orWhere('phone', 'like', "%{$keyword}%");
                  });
            });
        }

        // 2. Lọc theo trạng thái đơn hàng
=======
use Illuminate\Http\Request;
use App\Models\Order;

class OrderAdminController extends Controller
{
    // Hiển thị danh sách tất cả đơn hàng cho admin (có lọc trạng thái và phân trang)
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

<<<<<<< HEAD
        // 3. Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // 4. Lọc theo phương thức thanh toán
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->paginate(10)->withQueryString();

        // Thống kê nhanh cho Admin
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'revenue' => (float) Order::where('payment_status', 'paid')
                ->orWhere('status', 'completed')
                ->sum('total_price'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
=======
        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }

    // Xem chi tiết một đơn hàng bất kỳ của khách (phía admin)
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
<<<<<<< HEAD
        $trackingUrl = GhnService::getTrackingUrl($order->ghn_order_code);

        return view('admin.orders.show', compact('order', 'trackingUrl'));
    }

    // Cập nhật trạng thái đơn hàng (Chờ xử lý, Đang xử lý, Chờ giao hàng, Hoàn thành, Đã hủy)
=======
        return view('admin.orders.show', compact('order'));
    }

    // Cập nhật trạng thái đơn hàng (Đang xử lý, Đang giao, Đã giao, Hủy...)
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
<<<<<<< HEAD
            'payment_status' => 'nullable|in:pending,paid,failed',
        ]);

        $order = Order::with('orderItems')->findOrFail($id);

        // Nếu Admin xác nhận đã thanh toán cho đơn hàng online (chưa trừ kho trước đó)
        if ($request->payment_status === 'paid' && $order->payment_status !== 'paid' && $order->payment_method !== 'cod') {
            foreach ($order->orderItems as $item) {
                \App\Models\Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
            }
        }

        // Nếu đơn hàng bị hủy, hoàn trả lại tồn kho
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            if ($order->payment_status === 'paid' || $order->payment_method === 'cod') {
                foreach ($order->orderItems as $item) {
                    \App\Models\Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }
        }

        $order->status = $request->status;

        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng #' . $order->id . ' thành công!');
    }

    // Admin chủ động tạo mã vận đơn GHN cho đơn hàng
    public function createGhnOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $result = GhnService::createShippingOrder($order);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Tạo mã vận đơn GHN thành công! Mã đơn: ' . $result['order_code']);
        }

        return redirect()->back()->with('error', $result['message']);
=======
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
>>>>>>> c6ed5794fe53a6119504cc04070106a5146bd45d
    }
}