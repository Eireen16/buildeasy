<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->text('description');
            $table->integer('environmental_impact_rating')->default(1); // 1-5 stars
            $table->integer('carbon_footprint_rating')->default(1); // 1-5 stars
            $table->integer('recyclability_rating')->default(1); // 1-5 stars
            $table->decimal('sustainability_rating', 3, 2)->default(1.00); // calculated average
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
