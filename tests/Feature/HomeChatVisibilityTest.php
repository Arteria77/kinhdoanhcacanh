<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeChatVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_user_chat_for_regular_user(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response
            ->assertOk()
            ->assertSee('id="home-user-chat-box"', false)
            ->assertSee('Hỗ trợ khách hàng');
    }

    public function test_home_page_shows_admin_chat_for_admin_user(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/');

        $response
            ->assertOk()
            ->assertSee('id="home-admin-chat-box"', false)
            ->assertSee('Hỗ trợ Khách hàng');
    }

    public function test_user_can_send_message_to_admin(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin-chat@example.com',
        ]);

        $response = $this->actingAs($user)
            ->postJson('/chat/send', [
                'message' => 'Xin chào admin, tôi cần hỗ trợ',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('sender_id', $user->id)
            ->assertJsonPath('receiver_id', $admin->id)
            ->assertJsonPath('content', 'Xin chào admin, tôi cần hỗ trợ');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $user->id,
            'receiver_id' => $admin->id,
            'content' => 'Xin chào admin, tôi cần hỗ trợ',
        ]);
    }

    public function test_admin_can_list_customer_and_reply_to_them(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin-reply@example.com',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer-chat@example.com',
        ]);

        Message::create([
            'sender_id' => $customer->id,
            'receiver_id' => $admin->id,
            'content' => 'Tôi cần hỗ trợ về đơn hàng',
            'is_read' => false,
        ]);

        $this->actingAs($admin)
            ->getJson('/admin/chat/users')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $customer->id,
                'name' => $customer->name,
            ]);

        $this->actingAs($admin)
            ->getJson("/admin/chat/messages/{$customer->id}")
            ->assertOk()
            ->assertJsonFragment([
                'content' => 'Tôi cần hỗ trợ về đơn hàng',
                'sender_id' => $customer->id,
            ]);

        $this->actingAs($admin)
            ->postJson('/admin/chat/send', [
                'message' => 'Chúng tôi sẽ hỗ trợ bạn ngay',
                'user_id' => $customer->id,
            ])
            ->assertOk()
            ->assertJsonPath('sender_id', $admin->id)
            ->assertJsonPath('receiver_id', $customer->id)
            ->assertJsonPath('content', 'Chúng tôi sẽ hỗ trợ bạn ngay');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $admin->id,
            'receiver_id' => $customer->id,
            'content' => 'Chúng tôi sẽ hỗ trợ bạn ngay',
        ]);
    }
}
