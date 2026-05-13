<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('booking_form_fields')
            ->where('key', 'privacy')
            ->update([
                'label' => 'Ho letto l’Informativa Privacy e acconsento al trattamento dei miei dati per la gestione della richiesta.',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('booking_form_fields')
            ->where('key', 'privacy')
            ->update([
                'label' => 'Accetto di essere ricontattato per la gestione della richiesta.',
            ]);
    }
};
