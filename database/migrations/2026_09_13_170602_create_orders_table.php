<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết với bảng users
            $table->decimal('total_price', 10, 2); // Tổng tiền đơn hàng
            $table->string('status')->default('pending'); // Trạng thái đơn: pending (chờ xử lý), shipping (đang giao), completed (hoàn thành), cancelled (đã hủy)
            $table->string('shipping_name'); // Tên người nhận
            $table->string('shipping_phone'); // Số điện thoại nhận hàng
            $table->text('shipping_address'); // Địa chỉ nhận hàng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};