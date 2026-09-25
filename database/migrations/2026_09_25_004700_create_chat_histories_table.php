<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); // Phân biệt phiên chat của từng khách
            $table->enum('role', ['user', 'assistant']); // Ai là người nhắn (user hoặc bot)
            $table->text('message'); // Nội dung tin nhắn
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_histories');
    }
};
