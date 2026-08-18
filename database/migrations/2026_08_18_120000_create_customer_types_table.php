<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_static')->default(0);
            $table->timestamps();
        });

        // Seed initial customer types
        DB::table('customer_types')->insert([
            [
                'name' => 'Main Customer',
                'description' => 'Default customer type',
                'is_static' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Walking Customer',
                'description' => 'Static customer type for instant upfront payment',
                'is_static' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_types');
    }
};
