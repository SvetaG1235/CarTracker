<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_mileage_fields_to_reminders_table.php
public function up(): void {
    Schema::table('reminders', function (Blueprint $table) {
        $table->boolean('is_mileage_based')->default(false)->after('is_recurring');
        $table->unsignedInteger('mileage_interval')->nullable()->after('is_mileage_based');
        $table->unsignedInteger('next_mileage_due')->nullable()->after('mileage_interval');
    });
}
public function down(): void {
    Schema::table('reminders', function (Blueprint $table) {
        $table->dropColumn(['is_mileage_based', 'mileage_interval', 'next_mileage_due']);
    });
}
};
