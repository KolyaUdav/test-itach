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
            $table->string('fuel_name')->nullable();
            $table->string('fuel_type')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('cost_in_time')->default(0.00);
            $table->decimal('cost')->default(0.00);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
