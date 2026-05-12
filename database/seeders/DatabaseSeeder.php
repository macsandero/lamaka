<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\BookingFormField;
use App\Models\BookingFormSetting;
use App\Models\ContactSetting;
use App\Models\Experience;
use App\Models\HomepageContent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment(['local', 'testing']) || env('ADMIN_PASSWORD')) {
            User::query()->updateOrCreate(
                ['email' => env('ADMIN_EMAIL', 'admin@lamaka.local')],
                [
                    'name' => env('ADMIN_NAME', 'Admin Lamaka'),
                    'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                    'is_admin' => true,
                ],
            );
        }

        HomepageContent::query()->updateOrCreate(
            ['id' => 1],
            [
                'hero_eyebrow' => 'Cadore - Dolomiti',
                'hero_title' => 'Passeggiate nella natura con lama e alpaca',
                'hero_subtitle' => '<p>Esperienze lente tra lago, boschi e montagne. Un tempo sospeso da vivere insieme ai nostri animali.</p>',
                'hero_button_label' => 'Scopri le esperienze',
                'hero_button_anchor' => '#esperienze',
                'hero_video' => 'videos/hero-optimized.mp4',
                'experiences_eyebrow' => 'Esperienze',
                'experiences_title' => 'Natura, lentezza e relazione',
                'about_eyebrow' => 'Chi siamo',
                'about_title' => 'Un tempo lento da condividere',
                'about_body' => '<p>LAMAKA nasce dal desiderio di creare esperienze autentiche nella natura, accompagnati dal passo lento e silenzioso di lama e alpaca.</p><p>Tra boschi, montagne e paesaggi del Cadore, ogni passeggiata diventa un’occasione per rallentare, respirare e ritrovare una connessione semplice con gli animali e con il territorio.</p><p>Non una semplice attività turistica, ma un’esperienza da vivere insieme.</p>',
                'about_image' => 'images/about/chi-siamo.jpeg',
                'animals_eyebrow' => 'Gli animali',
                'animals_title' => 'Cinque personalità, un solo passo lento',
            ],
        );

        $experiences = [
            [
                'title' => 'Primo incontro',
                'description' => '<p>Una passeggiata semplice e immersiva per conoscere lama e alpaca, camminando tra lago, sentieri e natura.</p>',
                'experience_type' => '<p>Esperienza con alpaca ideale per famiglie, coppie, gruppi di persone o da soli</p>',
                'purpose' => '<p>Scopri il mondo di lama e alpaca con una passeggiata facile nei dintorni del Lago di Calalzo, perfetta per chi vuole provare per la prima volta questa attività.</p>',
                'experience_details' => '<ul><li>conoscerai gli animali da vicino</li><li>imparerai a condurli</li><li>camminerai immerso nella natura</li></ul>',
                'short_duration' => '30 min',
                'short_price' => '20€ a persona',
                'long_duration' => '1 ora',
                'long_price' => '35€ a persona',
                'ideal_for' => '<p>Ideale per chi cerca cosa fare a Calalzo di Cadore</p>',
                'image' => 'images/esperienze/Foto diAlpaca e lama completa.jpeg',
                'sort_order' => 10,
            ],
            [
                'title' => 'Passeggiata al tramonto',
                'description' => '<p>Un’esperienza lenta e romantica tra le montagne del Cadore, accompagnati dal ritmo calmo degli animali.</p>',
                'experience_type' => '<p>Esperienza nella natura al tramonto con lama e alpaca</p>',
                'purpose' => '<p>Vivi un momento lento e suggestivo tra luce, paesaggio e relazione con gli animali.</p>',
                'experience_details' => '<ul><li>camminerai al passo degli animali</li><li>vivrai il tramonto tra i panorami del Cadore</li><li>condividerai un’esperienza rilassante e autentica</li></ul>',
                'short_duration' => null,
                'short_price' => null,
                'long_duration' => '1 ora',
                'long_price' => '35€ a persona',
                'ideal_for' => '<p>Ideale per coppie, famiglie e piccoli gruppi</p>',
                'image' => 'images/esperienze/due lama al pascolo.jpeg',
                'sort_order' => 20,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::query()->updateOrCreate(
                ['title' => $experience['title']],
                [...$experience, 'is_active' => true],
            );
        }

        $animals = [
            ['name' => 'Athos', 'description' => 'Curioso e sempre attento a ciò che succede attorno a lui. Ama osservare le persone e avvicinarsi con delicatezza.', 'image' => 'images/animali/Athos.jpeg', 'sort_order' => 10],
            ['name' => 'Kairos', 'description' => 'Dolce e tranquillo, trasmette calma già dal primo incontro. È perfetto per chi cerca un momento di relax autentico.', 'image' => 'images/animali/Kairos.jpeg', 'sort_order' => 20],
            ['name' => 'Skiantos', 'description' => 'Il leader del gruppo. Sicuro di sé, curioso e sempre pronto ad aprire la strada durante le passeggiate.', 'image' => 'images/animali/Skiantos-2.jpeg', 'sort_order' => 30],
            ['name' => 'Gulliver', 'description' => 'Elegante e riflessivo, ama i ritmi lenti e le passeggiate silenziose immerso nella natura.', 'image' => 'images/animali/Gulliver.jpeg', 'sort_order' => 40],
            ['name' => 'Francis', 'description' => 'Affettuoso e socievole, crea subito empatia con adulti e bambini grazie al suo carattere gentile.', 'image' => 'images/animali/Francis.jpeg', 'sort_order' => 50],
        ];

        foreach ($animals as $animal) {
            Animal::query()->updateOrCreate(
                ['name' => $animal['name']],
                [...$animal, 'is_active' => true],
            );
        }

        ContactSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'business_name' => 'LAMAKA',
                'heading' => 'Contatti',
                'body' => 'Trovi LAMAKA in Cadore, tra Dolomiti, natura e passo lento. Per informazioni puoi scriverci o raggiungerci dai nostri canali.',
                'footer_body' => '<p>Esperienze lente tra Dolomiti, natura e passo calmo. Un luogo per ritrovare tempo, respiro e relazione con gli animali.</p>',
                'email' => null,
                'phone' => null,
                'whatsapp' => null,
                'address' => 'Cadore - Dolomiti',
                'map_query' => '46.446076,12.391663',
                'instagram_url' => null,
                'facebook_url' => null,
                'footer_note' => '<p>LAMAKA nasce per vivere la natura con rispetto, lentezza e attenzione agli animali.</p>',
                'directions_label' => 'Indicazioni stradali',
                'directions_url' => 'https://maps.app.goo.gl/VaD1nfvAs6LxYUPn8',
                'privacy_url' => null,
                'cookie_url' => null,
                'terms_url' => null,
                'legal_text' => '© Copyright LAMAKA',
                'booking_label' => 'Prenota',
                'booking_url' => null,
                'is_active' => true,
            ],
        );

        BookingFormSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'eyebrow' => 'Prenota',
                'heading' => 'Prenota la tua esperienza',
                'body' => 'Compila il modulo con le informazioni principali. Ti ricontatteremo per confermare disponibilità, dettagli e orari.',
                'image' => null,
                'submit_label' => 'Invia richiesta',
                'success_message' => 'Richiesta inviata correttamente. Ti ricontatteremo al più presto.',
                'is_active' => true,
            ],
        );

        $bookingFields = [
            ['label' => 'Nome e cognome', 'key' => 'nome', 'type' => 'text', 'placeholder' => 'Il tuo nome', 'sort_order' => 10, 'is_required' => true],
            ['label' => 'Email', 'key' => 'email', 'type' => 'email', 'placeholder' => 'nome@email.it', 'sort_order' => 20, 'is_required' => true],
            ['label' => 'Telefono', 'key' => 'telefono', 'type' => 'tel', 'placeholder' => '+39 ...', 'sort_order' => 30, 'is_required' => false],
            ['label' => 'Esperienza', 'key' => 'esperienza', 'type' => 'select', 'placeholder' => null, 'options' => "Primo incontro\nPasseggiata al tramonto\nAltro", 'sort_order' => 40, 'is_required' => true],
            ['label' => 'Giorno e ora preferiti', 'key' => 'data_ora_preferita', 'type' => 'datetime', 'placeholder' => null, 'sort_order' => 50, 'is_required' => true],
            ['label' => 'Numero partecipanti', 'key' => 'partecipanti', 'type' => 'number', 'placeholder' => null, 'sort_order' => 60, 'is_required' => false],
            ['label' => 'Messaggio', 'key' => 'messaggio', 'type' => 'textarea', 'placeholder' => 'Raccontaci esigenze, periodo o domande particolari.', 'sort_order' => 70, 'is_required' => false, 'is_full_width' => true],
            ['label' => 'Accetto di essere ricontattato per la gestione della richiesta.', 'key' => 'privacy', 'type' => 'checkbox', 'placeholder' => null, 'sort_order' => 80, 'is_required' => true, 'is_full_width' => true],
        ];

        foreach ($bookingFields as $field) {
            BookingFormField::query()->updateOrCreate(
                ['key' => $field['key']],
                [
                    'label' => $field['label'],
                    'type' => $field['type'],
                    'placeholder' => $field['placeholder'] ?? null,
                    'help_text' => null,
                    'options' => $field['options'] ?? null,
                    'is_required' => $field['is_required'],
                    'is_active' => true,
                    'is_full_width' => $field['is_full_width'] ?? false,
                    'sort_order' => $field['sort_order'],
                ],
            );
        }

        BookingFormField::query()
            ->where('key', 'data_preferita')
            ->update(['is_active' => false]);
    }
}
