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
    Schema::create('reminders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('car_id')->nullable()->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->enum('type', ['oil', 'coolant', 'brake_fluid', 'tires', 'inspection', 'custom']);
        $table->date('due_date');
        $table->boolean('is_recurring')->default(false);
        $table->enum('status', ['active', 'done', 'dismissed'])->default('active');
        $table->text('notes')->nullable();
        $table->timestamps();
        
        $table->index(['user_id', 'due_date', 'status']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
