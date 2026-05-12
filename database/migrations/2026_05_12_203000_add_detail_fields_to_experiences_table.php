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
            $table->string('experience_type')->nullable()->after('description');
            $table->text('purpose')->nullable()->after('experience_type');
            $table->text('experience_details')->nullable()->after('purpose');
            $table->string('short_duration')->nullable()->after('experience_details');
            $table->string('short_price')->nullable()->after('short_duration');
            $table->string('long_duration')->nullable()->after('short_price');
            $table->string('long_price')->nullable()->after('long_duration');
            $table->text('ideal_for')->nullable()->after('long_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn([
                'experience_type',
                'purpose',
                'experience_details',
                'short_duration',
                'short_price',
                'long_duration',
                'long_price',
                'ideal_for',
            ]);
        });
    }
};
