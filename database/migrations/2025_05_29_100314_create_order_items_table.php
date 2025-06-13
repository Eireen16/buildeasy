<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('material_variation_id')->nullable();
            $table->string('material_name'); // Store name at time of order
            $table->string('variation_name')->nullable(); // Store variation name at time of order
            $table->string('variation_value')->nullable(); // Store variation value at time of order
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->foreign('material_variation_id')->references('id')->on('material_variations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};