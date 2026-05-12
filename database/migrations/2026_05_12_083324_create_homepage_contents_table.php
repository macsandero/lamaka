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
        Schema::create('homepage_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_button_label')->nullable();
            $table->string('hero_button_anchor')->nullable();
            $table->string('hero_video')->nullable();
            $table->string('experiences_eyebrow')->nullable();
            $table->string('experiences_title')->nullable();
            $table->string('about_eyebrow')->nullable();
            $table->string('about_title')->nullable();
            $table->longText('about_body')->nullable();
            $table->string('about_image')->nullable();
            $table->string('animals_eyebrow')->nullable();
            $table->string('animals_title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_contents');
    }
};
