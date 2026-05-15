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
    Schema::table('users', function (Blueprint $table) {
        // We make them nullable so old users without addresses don't break the database
        $table->string('street')->nullable();
        $table->string('city')->nullable();
        $table->string('province')->nullable();
        $table->string('zip')->nullable();
    });
}

    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['street', 'city', 'province', 'zip']);
    });
}
};
