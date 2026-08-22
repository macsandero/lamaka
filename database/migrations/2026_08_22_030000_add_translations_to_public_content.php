<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'homepage_contents', 'experiences', 'animals', 'contact_settings',
        'booking_form_settings', 'booking_form_fields', 'legal_pages',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, fn (Blueprint $blueprint) => $blueprint->json('translations')->nullable());
        }

        $this->seedEnglishTranslations();
    }

    private function seedEnglishTranslations(): void
    {
        $set = fn (string $table, array $where, array $values) => DB::table($table)->where($where)
            ->update(['translations' => json_encode(['en' => $values], JSON_UNESCAPED_UNICODE)]);

        $set('homepage_contents', ['id' => 1], [
            'hero_eyebrow' => 'Cadore · Dolomites', 'hero_title' => 'Nature walks with llamas and alpacas',
            'hero_subtitle' => '<p>Slow experiences among lakes, forests and mountains. A suspended moment to share with our animals.</p>',
            'hero_button_label' => 'Discover the experiences', 'experiences_eyebrow' => 'Experiences',
            'experiences_title' => 'Nature, slowness and connection', 'about_eyebrow' => 'About us',
            'about_title' => 'Slow time to share',
            'about_body' => '<p>LAMAKA was born from the desire to create authentic experiences in nature, guided by the slow and quiet pace of llamas and alpacas.</p><p>Among the forests, mountains and landscapes of Cadore, every walk becomes a chance to slow down, breathe and reconnect with animals and the land.</p><p>More than a tourist activity: an experience to share.</p>',
            'animals_eyebrow' => 'Our animals', 'animals_title' => 'Five personalities, one gentle pace',
        ]);

        $set('booking_form_settings', ['id' => 1], [
            'eyebrow' => 'Book', 'heading' => 'Book your experience',
            'body' => 'Complete the form with the main information. We will contact you to confirm availability, details and times.',
            'submit_label' => 'Send request',
            'success_message' => 'Your request has been sent successfully. We will contact you shortly to arrange the activity time.',
        ]);

        $fieldTranslations = [
            'nome' => ['label' => 'Full name', 'placeholder' => 'Your name'],
            'email' => ['label' => 'Email'], 'telefono' => ['label' => 'Phone', 'placeholder' => '+39 ...'],
            'esperienza' => ['label' => 'Experience', 'options' => "First encounter\nSunset walk\nOther"],
            'data_ora_preferita' => ['label' => 'Preferred day'], 'partecipanti' => ['label' => 'Number of participants'],
            'messaggio' => ['label' => 'Message', 'placeholder' => 'Tell us about any needs, preferred period or questions.'],
            'privacy' => ['label' => 'I have read the Privacy Policy and consent to the processing of my data for handling this request.'],
        ];
        foreach ($fieldTranslations as $key => $values) {
            $set('booking_form_fields', ['key' => $key], $values);
        }

        $set('experiences', ['title' => 'Primo incontro'], [
            'title' => 'First encounter', 'description' => '<p>An easy, immersive walk to meet llamas and alpacas among lakes, trails and nature.</p>',
            'experience_type' => '<p>An alpaca experience ideal for families, couples, groups or solo visitors.</p>',
            'purpose' => '<p>Discover the world of llamas and alpacas on an easy walk around Lake Calalzo.</p>',
            'experience_details' => '<ul><li>meet the animals up close</li><li>learn how to lead them</li><li>walk surrounded by nature</li></ul>',
            'short_duration' => '30 min', 'short_price' => '€20 per person', 'long_duration' => '1 hour',
            'long_price' => '€35 per person', 'ideal_for' => '<p>Ideal for anyone looking for things to do in Calalzo di Cadore.</p>',
        ]);
        $set('experiences', ['title' => 'Passeggiata al tramonto'], [
            'title' => 'Sunset walk', 'description' => '<p>A slow, romantic experience in the Cadore mountains, following the gentle pace of the animals.</p>',
            'experience_type' => '<p>A sunset nature experience with llamas and alpacas.</p>',
            'purpose' => '<p>Enjoy a peaceful moment of light, landscape and connection with the animals.</p>',
            'experience_details' => '<ul><li>walk at the animals’ pace</li><li>watch the sunset over Cadore</li><li>share an authentic, relaxing experience</li></ul>',
            'long_duration' => '1 hour', 'long_price' => '€35 per person', 'ideal_for' => '<p>Ideal for couples, families and small groups.</p>',
        ]);

        $animalDescriptions = [
            'Athos' => 'Curious and always aware of everything around him. He loves observing people and approaching gently.',
            'Kairos' => 'Sweet and calm, he conveys serenity from the very first meeting.',
            'Skiantos' => 'The leader of the group: confident, curious and always ready to lead the way.',
            'Gulliver' => 'Elegant and thoughtful, he loves slow walks immersed in nature.',
            'Francis' => 'Affectionate and sociable, he immediately connects with adults and children.',
        ];
        foreach ($animalDescriptions as $name => $description) {
            $set('animals', ['name' => $name], ['name' => $name, 'description' => $description]);
        }

        $set('contact_settings', ['id' => 1], [
            'heading' => 'Contact us', 'footer_body' => '<p>Slow experiences in the Dolomites, surrounded by nature and guided by a gentle pace.</p>',
            'address' => 'Cadore · Dolomites', 'directions_label' => 'Directions',
            'footer_note' => '<p>LAMAKA was created to experience nature with respect, care and attention to animals.</p>',
            'booking_label' => 'Book now', 'legal_text' => '© Copyright LAMAKA',
        ]);
        $set('legal_pages', ['slug' => 'privacy-policy'], ['title' => 'Privacy Policy', 'body' => '<p>Enter the English Privacy Policy text here.</p>']);
        $set('legal_pages', ['slug' => 'cookie-policy'], ['title' => 'Cookie Policy', 'body' => '<p>Enter the English Cookie Policy text here.</p>']);
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropColumn('translations'));
        }
    }
};
