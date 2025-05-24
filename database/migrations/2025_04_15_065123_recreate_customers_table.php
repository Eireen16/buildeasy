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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('bank_details')->nullable()->change();

            // Add profile_picture if it doesn't exist yet
            if (!Schema::hasColumn('customers', 'profile_picture')) {
                $table->string('profile_picture')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // You can reverse the changes here if necessary
            $table->string('name')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('bank_details')->nullable(false)->change();

            $table->dropColumn('profile_picture');
        });
    }
};

