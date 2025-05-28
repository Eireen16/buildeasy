<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('material_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->string('variation_name'); // e.g., "Color", "Size"
            $table->string('variation_value'); // e.g., "Blue", "Large"
            $table->integer('stock'); // stock for this specific variation
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('material_variations');
    }
};