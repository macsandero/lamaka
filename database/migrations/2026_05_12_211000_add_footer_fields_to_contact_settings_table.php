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
        Schema::table('contact_settings', function (Blueprint $table) {
            $table->text('footer_body')->nullable()->after('body');
            $table->text('footer_note')->nullable()->after('instagram_url');
            $table->string('facebook_url')->nullable()->after('instagram_url');
            $table->string('directions_label')->nullable()->after('footer_note');
            $table->string('directions_url')->nullable()->after('directions_label');
            $table->string('privacy_url')->nullable()->after('directions_url');
            $table->string('cookie_url')->nullable()->after('privacy_url');
            $table->string('terms_url')->nullable()->after('cookie_url');
            $table->string('legal_text')->nullable()->after('terms_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_settings', function (Blueprint $table) {
            $table->dropColumn([
                'footer_body',
                'footer_note',
                'facebook_url',
                'directions_label',
                'directions_url',
                'privacy_url',
                'cookie_url',
                'terms_url',
                'legal_text',
            ]);
        });
    }
};
