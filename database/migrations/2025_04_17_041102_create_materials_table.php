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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->json('images'); 
            $table->json('variations'); 
            $table->text('description');
    
            // Ratings (stored as integers from 1 to 5)
            $table->tinyInteger('environmental_impact_rating');
            $table->tinyInteger('carbon_footprint_rating');
            $table->tinyInteger('recyclability_rating');
    
            // Sustainability rating (average of the three)
            $table->decimal('sustainability_rating', 3, 2);
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
