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
    Schema::create('stores', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('location');
        $table->string('logo')->nullable();
        $table->string('banner')->nullable();
        $table->decimal('rating', 2, 1)->default(0.0); // e.g., 4.8
        $table->integer('followers')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
