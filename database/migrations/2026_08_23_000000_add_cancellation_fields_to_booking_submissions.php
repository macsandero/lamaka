<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_submissions', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('confirmed_at');
            $table->string('cancellation_reason_other')->nullable()->after('cancellation_reason');
            $table->timestamp('cancelled_at')->nullable()->index()->after('cancellation_reason_other');
        });
    }

    public function down(): void
    {
        Schema::table('booking_submissions', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancellation_reason_other', 'cancelled_at']);
        });
    }
};
