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
        if (!Schema::hasTable('redeemed_coupons')) {
            Schema::create('redeemed_coupons', function (Blueprint $table) {
                $table->bigIncrements('redeemed_coupon_id')->autoIncrement();
                $table->bigInteger('user_id')->index();
                $table->bigInteger('coupon_id')->nullable()->index();
                $table->string('coupon_code')->nullable();
                $table->decimal('coupon_value')->nullable();
                $table->enum('coupon_status', ['active', 'inactive'])->default('active')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeemed_coupons');
    }
};
