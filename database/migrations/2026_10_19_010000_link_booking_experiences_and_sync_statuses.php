<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_submissions', function (Blueprint $table) {
            $table->foreignId('experience_id')->nullable()->constrained()->nullOnDelete();
        });

        $experiences = DB::table('experiences')->get();
        DB::table('booking_submissions')->orderBy('id')->each(function (object $booking) use ($experiences): void {
            $name = data_get(json_decode($booking->data, true), 'esperienza.value');
            $experience = $name ? $experiences->first(function (object $experience) use ($name): bool {
                return $experience->title === $name
                    || data_get(json_decode($experience->translations ?? '{}', true), 'en.title') === $name;
            }) : null;

            DB::table('booking_submissions')->where('id', $booking->id)->update([
                'experience_id' => $experience?->id,
                'confirmed_at' => $booking->status === 'confirmed'
                    ? ($booking->confirmed_at ?? $booking->updated_at ?? $booking->created_at ?? now())
                    : null,
                'cancelled_at' => $booking->status === 'cancelled'
                    ? ($booking->cancelled_at ?? $booking->updated_at ?? $booking->created_at ?? now())
                    : null,
                'cancellation_reason' => $booking->status === 'cancelled' ? $booking->cancellation_reason : null,
                'cancellation_reason_other' => $booking->status === 'cancelled' ? $booking->cancellation_reason_other : null,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('booking_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('experience_id');
        });
    }
};
