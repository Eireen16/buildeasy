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
    Schema::create('suppliers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('profile_picture')->nullable();
        $table->string('company_name');
        $table->string('license_number');
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->string('location')->nullable();
        $table->string('bank_details')->nullable();
        $table->boolean('is_approved')->default(false);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
