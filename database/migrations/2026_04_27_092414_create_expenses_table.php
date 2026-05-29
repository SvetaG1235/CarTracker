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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('car_id')->constrained()->cascadeOnDelete();
        $table->enum('category', ['fuel', 'wash', 'repair', 'maintenance', 'insurance', 'other']);
        $table->decimal('amount', 10, 2);
        $table->date('date');
        $table->string('description')->nullable();
        $table->string('receipt_path')->nullable();
        $table->timestamps();
        
        $table->index(['user_id', 'date']);
        $table->index(['car_id', 'category']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
