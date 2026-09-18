<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã giảm giá (VD: VUIKHOE, GIAM50K)
            $table->enum('type', ['fixed', 'percent']); // Loại: số tiền cố định hoặc phần trăm
            $table->decimal('value', 10, 2); // Giá trị (VD: 50000 hoặc 10 cho 10%)
            $table->decimal('min_order_value', 10, 2)->default(0); // Đơn tối thiểu
            $table->integer('usage_limit')->nullable(); // Giới hạn lượt dùng
            $table->integer('used_count')->default(0); // Số lượt đã dùng
            $table->dateTime('expires_at')->nullable(); // Ngày hết hạn
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
