<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_form_settings', function (Blueprint $table) {
            $table->boolean('bookings_suspended')->default(false);
            $table->date('booking_suspension_until')->nullable();
            $table->text('booking_suspension_message')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('booking_form_settings', function (Blueprint $table) {
            $table->dropColumn(['bookings_suspended', 'booking_suspension_until', 'booking_suspension_message']);
        });
    }
};
