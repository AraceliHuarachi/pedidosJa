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
        Schema::create('order_user_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_user_id')->constrained('order_users')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('quantity');
            $table->text('description')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->decimal('final_price', 7, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_user_products');
    }
};
