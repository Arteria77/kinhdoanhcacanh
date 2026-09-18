<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_product_even_if_it_was_used_in_an_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $product = Product::create([
            'name' => 'Cá cảnh A',
            'price' => 100000,
            'stock' => 10,
            'description' => 'Mô tả',
            'image' => 'products/a.jpg',
        ]);

        $order = Order::create([
            'user_id' => $admin->id,
            'total_price' => 100000,
            'status' => 'pending',
            'shipping_name' => 'Nguyễn Văn A',
            'shipping_phone' => '0909123456',
            'shipping_address' => '123 Test',
            'shipping_fee' => 15000,
            'coupon_code' => null,
            'discount_amount' => 0,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100000,
        ]);

        $response = $this->delete(route('admin.products.destroy', $product->id));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Xóa sản phẩm thành công!');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
