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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // Người gửi
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); 
            // Người nhận[cite: 1]
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); 
            // Nội dung tin nhắn[cite: 1]
            $table->text('content'); 
            // Trạng thái đã đọc hay chưa[cite: 1]
            $table->boolean('is_read')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};