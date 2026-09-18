<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutCouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_apply_coupon_and_place_order_with_payment_method(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $product = Product::create([
            'name' => 'Cá cảnh A',
            'price' => 100000,
            'stock' => 20,
            'description' => 'Mô tả mẫu',
            'image' => 'products/sample.jpg',
        ]);

        $this->actingAs($user);

        session()->put('cart', [
            $product->id => [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'stock' => $product->stock,
                'image' => $product->image,
            ],
        ]);

        Coupon::create([
            'code' => 'SAVE10',
            'type' => 'fixed',
            'value' => 25000,
            'min_order_value' => 100000,
            'usage_limit' => 5,
            'used_count' => 0,
            'expires_at' => now()->addDay(),
        ]);

        $this->post('/coupon/apply', [
            'code' => 'SAVE10',
            'shipping_name' => 'Nguyễn Văn A',
            'shipping_phone' => '0909123456',
            'province_id' => '201',
            'district_id' => '1234',
            'to_district_id' => '1234',
            'ward_id' => '5678',
            'to_ward_code' => '5678',
            'shipping_address' => '123 Lê Lợi',
            'shipping_fee' => '15000',
            'payment_method' => 'vnpay',
        ])
            ->assertRedirect();

        $this->assertSame('SAVE10', session('coupon.code'));
        $this->get(route('checkout'))
            ->assertStatus(200)
            ->assertSee('formaction="' . route('coupon.remove') . '"', false)
            ->assertSee('value="0909123456"', false)
            ->assertSee('123 Lê Lợi');

        $response = $this->post('/orders', [
            'shipping_name' => 'Nguyễn Văn A',
            'shipping_phone' => '0909123456',
            'shipping_address' => '123 Lê Lợi',
            'to_district_id' => 1,
            'to_ward_code' => '12345',
            'shipping_fee' => 15000,
            'payment_method' => 'cod',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', ['id' => $order->id]));
        $this->assertSame('SAVE10', $order->coupon_code);
        $this->assertSame(25000.0, (float) $order->discount_amount);
        $this->assertSame(90000.0, (float) $order->total_price);
        $this->assertSame('cod', $order->payment_method);
        $this->assertSame(15000.0, (float) $order->shipping_fee);
        $this->assertSame(19, $product->fresh()->stock);

        $this->get(route('checkout.success', ['id' => $order->id]))
            ->assertStatus(200)
            ->assertSee('Thanh toán thành công')
            ->assertSee($order->shipping_name)
            ->assertSee($order->shipping_phone)
            ->assertSee(number_format($order->total_price, 0, ',', '.'));

        $this->get(route('cart.success'))
            ->assertStatus(200)
            ->assertSee('Thêm vào giỏ hàng thành công!');
    }
}
