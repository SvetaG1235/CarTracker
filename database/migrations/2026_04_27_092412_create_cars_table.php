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
    Schema::create('cars', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('brand');
        $table->string('model');
        $table->year('year')->nullable();
        $table->string('vin')->nullable()->unique();
        $table->string('plate')->nullable();
        $table->unsignedInteger('mileage')->default(0);
        $table->text('notes')->nullable();
        $table->timestamps();
        
        $table->index(['user_id', 'brand', 'model']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
