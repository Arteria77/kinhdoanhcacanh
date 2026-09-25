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
    Schema::create('chat_messages', function (Blueprint $table) {
        $table->id();
        $table->string('session_id')->index(); // Dùng session_id để phân biệt các khách hàng
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Nếu đã đăng nhập
        $table->string('role'); // 'user' hoặc 'model'
        $table->text('message');
        $table->timestamps();
    });
}
};
