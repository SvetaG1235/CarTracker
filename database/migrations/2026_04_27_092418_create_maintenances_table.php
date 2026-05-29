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
    Schema::create('maintenances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('car_id')->constrained()->cascadeOnDelete();
        $table->string('service_type');
        $table->string('part_name')->nullable();
        $table->date('date');
        $table->unsignedInteger('mileage_at');
        $table->unsignedInteger('next_mileage')->nullable();
        $table->date('next_due_date')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        
        $table->index(['car_id', 'date']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
