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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'shipping_fee')) {
                $table->decimal('shipping_fee', 10, 2)->default(0)->after('total_price');
            }

            if (! Schema::hasColumn('orders', 'to_district_id')) {
                $table->unsignedInteger('to_district_id')->nullable()->after('shipping_address');
            }

            if (! Schema::hasColumn('orders', 'to_ward_code')) {
                $table->string('to_ward_code')->nullable()->after('to_district_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_fee')) {
                $table->dropColumn('shipping_fee');
            }

            if (Schema::hasColumn('orders', 'to_district_id')) {
                $table->dropColumn('to_district_id');
            }

            if (Schema::hasColumn('orders', 'to_ward_code')) {
                $table->dropColumn('to_ward_code');
            }
        });
    }
};
