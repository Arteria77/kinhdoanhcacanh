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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Tên sản phẩm
            $table->decimal('price', 12, 2);        // Giá sản phẩm
            $table->integer('stock');               // Số lượng tồn kho
            $table->text('description')->nullable();// Mô tả chi tiết
            $table->string('image')->nullable();    // Đường dẫn ảnh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};