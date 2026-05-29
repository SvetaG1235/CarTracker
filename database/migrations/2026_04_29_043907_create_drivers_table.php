<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('drivers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('car_id')->constrained()->cascadeOnDelete();
        $table->string('full_name');
        $table->string('license_number')->nullable();
        $table->string('phone')->nullable();
        $table->boolean('is_primary')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
