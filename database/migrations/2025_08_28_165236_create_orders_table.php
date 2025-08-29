<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pressing_id')->constrained('pressings')->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'delivered'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->dateTime('pickup_date');
            $table->dateTime('confirmed_pickup_date')->nullable();
            $table->text('client_address');
            $table->text('special_instructions')->nullable();
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
