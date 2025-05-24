<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First check if the table exists, if not create it
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('profile_picture')->nullable();
                $table->string('name')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->string('bank_details')->nullable();
                $table->timestamps();
            });
        } else {
            // If table exists, make sure it has all necessary columns
            Schema::table('customers', function (Blueprint $table) {
                if (!Schema::hasColumn('customers', 'user_id')) {
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                }
                
                if (!Schema::hasColumn('customers', 'profile_picture')) {
                    $table->string('profile_picture')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'name')) {
                    $table->string('name')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'phone')) {
                    $table->string('phone')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'address')) {
                    $table->text('address')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'bank_details')) {
                    $table->string('bank_details')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        // No destructive actions in down method for safety
    }
};