<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_their_order_history_and_detail(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'name' => 'Cá tetra',
            'price' => 150000,
            'stock' => 10,
            'description' => 'Cá nước ngọt',
            'image' => 'products/demo.jpg',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 150000,
            'shipping_fee' => 30000,
            'status' => 'pending',
            'shipping_name' => 'Nguyễn Văn A',
            'shipping_phone' => '0901234567',
            'shipping_address' => '123 Lê Lợi, Đà Nẵng',
            'coupon_code' => null,
            'discount_amount' => 0,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 150000,
        ]);

        $response = $this->actingAs($user)->get('/orders');
        $response->assertOk();
        $response->assertSee('Đơn hàng của tôi');
        $response->assertSee('#' . $order->id);

        $detailResponse = $this->actingAs($user)->get('/orders/' . $order->id);
        $detailResponse->assertOk();
        $detailResponse->assertSee('Chi tiết đơn hàng');
        $detailResponse->assertSee('Cá tetra');
    }
}
