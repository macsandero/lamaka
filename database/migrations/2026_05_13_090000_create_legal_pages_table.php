<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('legal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('body')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('legal_pages')->insert([
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'body' => '<p>Inserisci qui il testo della Privacy Policy.</p>',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cookie-policy',
                'title' => 'Cookie Policy',
                'body' => '<p>Inserisci qui il testo della Cookie Policy.</p>',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_pages');
    }
};
