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
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('third_duration')->nullable()->after('long_price');
            $table->string('third_price')->nullable()->after('third_duration');
            $table->string('fourth_duration')->nullable()->after('third_price');
            $table->string('fourth_price')->nullable()->after('fourth_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn([
                'third_duration',
                'third_price',
                'fourth_duration',
                'fourth_price',
            ]);
        });
    }
};
