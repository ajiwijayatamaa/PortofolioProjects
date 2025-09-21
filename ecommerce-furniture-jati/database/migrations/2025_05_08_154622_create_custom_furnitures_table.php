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
        Schema::create('custom_furnitures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('type');
            $table->string('size');
            $table->string('finishing');
            $table->date('deadline');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_furnitures');
    }
};
