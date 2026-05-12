<?php

namespace Database\Seeders;

use App\Models\Animal;
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
            ['title' => 'Primo incontro', 'description' => 'Una passeggiata semplice e immersiva per conoscere lama e alpaca, camminando tra lago, sentieri e natura.', 'image' => 'images/esperienze/Foto diAlpaca e lama completa.jpeg', 'sort_order' => 10],
            ['title' => 'Passeggiata al tramonto', 'description' => 'Un’esperienza lenta e romantica tra le montagne del Cadore, accompagnati dal ritmo calmo degli animali.', 'image' => 'images/esperienze/due lama al pascolo.jpeg', 'sort_order' => 20],
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
                'heading' => 'Prenota la tua esperienza',
                'body' => 'Per informazioni, disponibilità e prenotazioni puoi contattarci direttamente. Ti risponderemo con i dettagli più adatti alla stagione e al gruppo.',
                'email' => null,
                'phone' => null,
                'whatsapp' => null,
                'address' => 'Cadore - Dolomiti',
                'instagram_url' => null,
                'booking_label' => 'Contattaci',
                'booking_url' => null,
                'is_active' => true,
            ],
        );
    }
}
