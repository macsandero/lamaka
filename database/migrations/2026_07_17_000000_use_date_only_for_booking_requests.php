<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('booking_form_fields')
            ->where('key', 'data_ora_preferita')
            ->update([
                'label' => 'Giorno preferito',
                'type' => 'date',
            ]);

        DB::table('booking_form_settings')
            ->where('is_active', true)
            ->update([
                'success_message' => "Richiesta inviata correttamente. Sarai contattato al più presto per concordare l'orario dell'attività",
            ]);
    }

    public function down(): void
    {
        DB::table('booking_form_fields')
            ->where('key', 'data_ora_preferita')
            ->update([
                'label' => 'Giorno e ora preferiti',
                'type' => 'datetime',
            ]);

        DB::table('booking_form_settings')
            ->where('is_active', true)
            ->update([
                'success_message' => 'Richiesta inviata correttamente. Ti ricontatteremo al più presto.',
            ]);
    }
};
