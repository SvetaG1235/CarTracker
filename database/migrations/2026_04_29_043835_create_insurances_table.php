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
    Schema::create('insurances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('car_id')->constrained()->cascadeOnDelete();
        $table->enum('type', ['osago', 'casco']);
        $table->string('policy_number');
        $table->string('company');
        $table->date('start_date');
        $table->date('end_date');
        $table->decimal('cost', 10, 2)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
