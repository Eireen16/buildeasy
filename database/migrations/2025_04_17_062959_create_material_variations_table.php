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
    Schema::create('material_variations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('material_id')->constrained()->onDelete('cascade');
        $table->string('variation_name'); // e.g. "Blue", "Large"
        $table->integer('stock')->default(0);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_variations');
    }
};
