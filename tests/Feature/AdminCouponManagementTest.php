<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCouponManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_coupon(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->post('/admin/coupons', [
            'code' => 'SAVE20',
            'type' => 'percent',
            'value' => 20,
            'min_order_value' => 200000,
            'usage_limit' => 10,
            'expires_at' => now()->addWeek()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'code' => 'SAVE20',
            'type' => 'percent',
            'value' => 20,
        ]);
    }
}
