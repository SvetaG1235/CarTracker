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
    Schema::table('service_cards', function (Blueprint $table) {
        $table->string('service_card_number')->nullable()->after('workshop_name');
        $table->string('barcode_image')->nullable()->after('service_card_number');
    });
}
public function down(): void {
    Schema::table('service_cards', function (Blueprint $table) {
        $table->dropColumn(['service_card_number', 'barcode_image']);
    });
}

    /**
     * Reverse the migrations.
     */

};
