<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // earn_points, redeem_points, coupon, free_item, birthday, minimum_spend, tier
            $table->string('benefit_type'); // fixed_discount, percent_discount, free_item, bonus_points, points_multiplier
            $table->decimal('benefit_value', 10, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->integer('priority')->default(0);
            $table->decimal('min_purchase', 10, 2)->default(0);
            $table->integer('required_points')->default(0);
            $table->string('customer_tier')->nullable();
            $table->json('eligible_product_ids')->nullable();
            $table->json('eligible_category_ids')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('per_customer_limit')->nullable();
            $table->boolean('is_stackable')->default(true);
            $table->string('coupon_code')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_rules');
    }
};
