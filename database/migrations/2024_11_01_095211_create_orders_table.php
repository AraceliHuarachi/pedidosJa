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
            $table->text('reason')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->unsignedBigInteger('delivery_user_id');
            $table->foreign('delivery_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('order_date');
            $table->enum('state', ['draft', 'in_process', 'completed', 'canceled'])->default('draft');
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
