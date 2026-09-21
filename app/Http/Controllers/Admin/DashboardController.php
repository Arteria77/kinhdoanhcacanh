<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tổng doanh thu thực tế (đã thanh toán hoặc hoàn thành)
        $totalRevenue = (float) Order::where(function ($q) {
            $q->where('payment_status', 'paid')
              ->orWhere('status', 'completed');
        })->sum('total_price');

        // Doanh thu hôm nay
        $todayRevenue = (float) Order::whereDate('created_at', Carbon::today())
            ->where(function ($q) {
                $q->where('payment_status', 'paid')
                  ->orWhere('status', 'completed');
            })->sum('total_price');

        // Doanh thu tháng này
        $thisMonthRevenue = (float) Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where(function ($q) {
                $q->where('payment_status', 'paid')
                  ->orWhere('status', 'completed');
            })->sum('total_price');

        // 2. Doanh thu theo phương thức thanh toán
        $revenueByMethod = [
            'sepay' => (float) Order::where('payment_method', 'sepay')
                ->where('payment_status', 'paid')
                ->sum('total_price'),
            'vnpay' => (float) Order::where('payment_method', 'vnpay')
                ->where('payment_status', 'paid')
                ->sum('total_price'),
            'cod' => (float) Order::where('payment_method', 'cod')
                ->where(function ($q) {
                    $q->where('payment_status', 'paid')
                      ->orWhere('status', 'completed');
                })->sum('total_price'),
        ];

        // 3. Thống kê số lượng đơn hàng theo trạng thái
        $orderCounts = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipping' => Order::where('status', 'shipping')->count(), // Chờ giao hàng / Vận đơn GHN
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // 4. Doanh thu 7 ngày gần nhất
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $displayDate = Carbon::now()->subDays($i)->format('d/m');
            
            $rev = (float) Order::whereDate('created_at', $date)
                ->where(function ($q) {
                    $q->where('payment_status', 'paid')
                      ->orWhere('status', 'completed');
                })->sum('total_price');

            $count = Order::whereDate('created_at', $date)->count();

            $dailyRevenue[] = [
                'date' => $displayDate,
                'revenue' => $rev,
                'count' => $count,
            ];
        }

        // 5. Thống kê danh mục loài cá, sản phẩm, khách hàng
        $counts = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'users' => User::where('role', '!=', 'admin')->count(),
        ];

        // 6. Đơn hàng mới nhất
        $recentOrders = Order::with('user')->latest()->take(6)->get();

        // 7. Top loài cá bán chạy nhất
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(price * quantity) as total_sales'))
            ->with('product.category')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'todayRevenue',
            'thisMonthRevenue',
            'revenueByMethod',
            'orderCounts',
            'dailyRevenue',
            'counts',
            'recentOrders',
            'topProducts'
        ));
    }
}
