<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $fill = function (string $table, array $where, array $english): void {
            $record = DB::table($table)->where($where)->first();
            if (! $record) {
                return;
            }

            $translations = json_decode($record->translations ?? '{}', true) ?: [];
            $translations['en'] = array_replace($english, $translations['en'] ?? []);
            DB::table($table)->where('id', $record->id)->update([
                'translations' => json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
        };

        $fill('experiences', ['title' => 'Passeggiata del mattino'], [
            'title' => 'Morning walk',
            'description' => '<p>At 8:00 am — A simple, relaxing experience away from mass tourism, with a short final break to enjoy the scenery, watch the animals and unwind by the lake.</p>',
            'experience_type' => '<p>A guided, slow, relaxing and immersive experience with llamas and alpacas.<br>Ideal for families, couples, small groups, solo visitors and anyone looking for contact with nature without excessive physical effort.</p>',
            'purpose' => '<p>To offer an experience of wellbeing, calm and connection with animals and nature.</p><p>The walk invites you to slow down, follow the rhythm of llamas and alpacas and enjoy a light-hearted moment away from the rush of everyday life.</p>',
            'experience_details' => '<ul><li>Meet llamas and alpacas up close</li><li>Learn to lead them calmly and respectfully</li><li>Walk slowly through nature, passing Lake Centro Cadore and Lagole</li><li>Discover interesting facts about their personalities and habits</li><li>Enjoy a final moment of relaxation</li><li>Sit, watch the animals and take in the scenery</li></ul>',
            'short_duration' => '1 hour', 'short_price' => '€35 per person',
            'long_duration' => '1 hour', 'long_price' => '€60 per family',
            'third_duration' => '1½ hours', 'third_price' => '€55 per person',
            'duration_notes' => "A family means parents with children, up to 4 people in total with one animal.\nChildren under 10 participating individually must lead the animal together with an adult family member.",
            'ideal_for' => '<p>Ideal for families, couples, small groups, solo visitors and anyone looking for contact with nature without excessive physical effort.</p>',
        ]);

        $fill('experiences', ['title' => 'Il salotto LAMAKA'], [
            'title' => 'The LAMAKA lounge',
            'description' => '<p>A gentle, relaxing break beside llamas and alpacas. A simple, light experience open to everyone, designed to enjoy the animals’ company without rushing or responsibility.</p>',
            'experience_type' => '<p>A relaxing, contemplative and photographic experience.<br>Ideal for anyone seeking nature, calm, tenderness and something different from the ordinary.</p>',
            'purpose' => '<p>To offer a space of peace and wellbeing where guests can slow down, observe the animals in their environment and enjoy authentic contact without having to “do” anything.</p>',
            'experience_details' => '<p>Guests can sit near the animals’ area, observe them, listen to facts and little stories about llamas and alpacas, take photos and enjoy a peaceful moment in nature accompanied by our staff.</p>',
            'short_duration' => '30 minutes', 'short_price' => '€15 per person',
            'long_duration' => '1 hour', 'long_price' => '€25',
            'duration_notes' => 'Children under 10 participating individually must lead the animal together with an adult family member.',
            'ideal_for' => '<p>Adults, couples, families and accompanied children.</p>',
        ]);

        $fill('experiences', ['title' => 'Custode per un giorno'], [
            'title' => 'Keeper for a day',
            'description' => '<p>A guided experience offering a gentle introduction to the daily lives of llamas and alpacas. A chance to observe them up close, get to know them and discover simple acts of care.</p>',
            'experience_type' => '<p>An educational and emotional hands-on animal experience.</p>',
            'purpose' => '<p>To introduce guests to the world of llamas and alpacas in a respectful, slow and mindful way.<br>The aim is to experience the value of care, listening and a gentle relationship with animals.</p>',
            'experience_details' => '<p>Guests are accompanied while they observe the animals, learn about their habits, discover how to communicate with them and take part in simple care activities, always safely and with no previous experience required.</p>',
            'short_duration' => '30 minutes', 'short_price' => '€15 per person',
            'long_duration' => '1 hour', 'long_price' => '€25 per person',
            'duration_notes' => 'Children under 10 participating individually must lead the animal together with an adult family member.',
            'ideal_for' => '<p>Adults, families and accompanied children.</p>',
        ]);

        $fill('experiences', ['title' => 'Attività di gruppo'], [
            'title' => 'Group activities',
            'description' => '<p>A supervised introductory experience inside the enclosure, with group activities alongside the animals and a final wool workshop.</p>',
            'experience_type' => '<p>A relaxing, educational and engaging activity suitable for families, children, groups, schools and people with disabilities.</p>',
            'purpose' => '<ul><li><strong>Build trust</strong> by learning to establish a respectful relationship with animals.</li><li><strong>Strengthen self-esteem</strong> through new experiences and communication.</li><li><strong>Develop courage</strong> by gradually overcoming fears and uncertainty.</li><li><strong>Increase self-awareness</strong> by noticing how our attitude affects the animals.</li><li><strong>Improve observation</strong> of body language, non-verbal signals and the surroundings.</li><li><strong>Encourage teamwork</strong> through listening and cooperation.</li><li><strong>Develop empathy and respect</strong> for the animals’ needs and pace.</li><li><strong>Practise calm and presence</strong> by slowing down.</li><li><strong>Encourage creativity and manual skills</strong> in the wool workshop.</li><li><strong>Reconnect with nature</strong> through an outdoor experience.</li></ul>',
            'experience_details' => '<ul><li>Welcome and introduction to the llamas and alpacas</li><li>A gradual approach that respects the animals’ pace</li><li>Group activities inside the enclosure</li><li>Observation of behaviour and non-verbal language</li><li>Safe, guided games and interactions</li><li>Discovering the qualities of llama and alpaca wool</li><li>A final creative wool workshop</li></ul>',
            'short_duration' => '2 hours', 'short_price' => '€55',
            'long_duration' => '3 hours', 'long_price' => '€70',
            'duration_notes' => 'Prices for schools, disability groups and large groups are agreed by phone.',
            'ideal_for' => '<p>Everyone.</p>',
        ]);

        $privacy = '<p><strong>Data Controller</strong></p><p>The Data Controller is:</p><p><strong>Federico Toson</strong><br>Loc. Colle dei Cai<br>32042 Calalzo di Cadore (BL)<br>Italy</p><p>Email: <a href="mailto:info@lamaka.it">info@lamaka.it</a><br>Website: <a href="https://www.lamaka.it/">https://www.lamaka.it</a></p><p><strong>Types of data collected</strong></p><p>The website may collect personal data voluntarily provided by users, including:</p><ul><li>full name</li><li>email address</li><li>telephone number</li><li>information relating to booking requests or participation in experiences</li></ul><p>Technical data may also be collected while browsing, including IP address, device and browser type, pages visited, time spent and technical browsing data.</p><p><strong>Purposes of processing</strong></p><p>Data is processed to respond to enquiries, manage bookings and participation requests, organise experiences and services, improve the website experience and comply with administrative, tax and legal obligations.</p><p><strong>Legal basis</strong></p><p>Processing is based on user consent, steps taken at the user’s request before entering into a contract or making a booking, legal obligations and the Controller’s legitimate interest in managing the website and its activities.</p><p><strong>Processing methods</strong></p><p>Appropriate technical and organisational measures are used to ensure security, confidentiality, protection against unauthorised access and prevention of data loss or improper disclosure.</p><p><strong>Data retention</strong></p><p>Data is retained only for as long as necessary for the purposes for which it was collected and in any event for no longer than 24 months, unless longer retention is required by law or for administrative, tax or legal reasons.</p><p><strong>Disclosure to third parties</strong></p><p>Data may be processed by technical service providers required to operate the website, including hosting, email and IT services. These parties process data in accordance with applicable law and, where necessary, are appointed as data processors. Data is not disclosed publicly.</p><p><strong>Cookies and tracking technologies</strong></p><p>The website uses technical cookies required for its operation. With the user’s consent, statistical or traffic-analysis tools may be used to improve the services offered. Please see the Cookie Policy for further information.</p><p><strong>User rights</strong></p><p>Users may at any time access, correct or erase their personal data, restrict or object to processing and request data portability. Requests may be sent to info@lamaka.it under Articles 15–22 of Regulation (EU) 2016/679 (GDPR).</p><p><strong>Changes to this policy</strong></p><p>This Privacy Policy may be updated to reflect legal, technical or organisational changes. Updated versions will be published on this page.</p><p><strong>Contact</strong></p><p>For any request concerning personal data processing: info@lamaka.it</p>';
        $fill('legal_pages', ['slug' => 'privacy-policy'], ['title' => 'Privacy Policy', 'body' => $privacy]);

        $fill('contact_settings', ['id' => 1], [
            'heading' => 'Contact us',
            'footer_body' => '<p><em>Nature walks with alpacas and llamas</em></p>',
            'address' => 'Lagole, Calalzo di Cadore',
            'directions_label' => 'Directions',
            'instagram_note' => '<p><em>Follow us to discover walks, emotions and the daily lives of our alpacas and llamas.</em></p>',
        ]);
    }

    public function down(): void {}
};
