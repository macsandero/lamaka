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
            $table->date('booking_date')->nullable()->index();
            $table->unsignedInteger('participants')->nullable();
            $table->unsignedInteger('animals')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('source')->nullable();
            $table->string('source_other')->nullable();
            $table->string('origin')->default('website')->index();
            $table->timestamp('confirmed_at')->nullable()->index();
        });

        DB::table('booking_submissions')->orderBy('id')->each(function (object $booking): void {
            $data = json_decode($booking->data, true) ?: [];
            $value = fn (string $key) => $data[$key]['value'] ?? null;

            DB::table('booking_submissions')->where('id', $booking->id)->update([
                'booking_date' => $value('data_ora_preferita') ?: $value('data_preferita'),
                'participants' => is_numeric($value('partecipanti')) ? (int) $value('partecipanti') : null,
                'customer_name' => $value('nome') ?: $value('name'),
                'phone' => $value('telefono'),
                'email' => $value('email'),
                'source' => 'Sito web',
                'origin' => 'website',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('booking_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'booking_date', 'participants', 'animals', 'customer_name', 'phone',
                'email', 'source', 'source_other', 'origin', 'confirmed_at',
            ]);
        });
    }
};
