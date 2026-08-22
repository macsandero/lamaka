<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('legal_pages')->where('slug', 'privacy-policy')->first();

        if (! $page) {
            return;
        }

        $translations = json_decode($page->translations ?? '{}', true) ?: [];
        $currentEnglishBody = data_get($translations, 'en.body');

        if (filled($currentEnglishBody) && ! str_contains($currentEnglishBody, 'Enter the English Privacy Policy')) {
            return;
        }

        $translations['en']['title'] = 'Privacy Policy';
        $translations['en']['body'] = '<p><strong>Data Controller</strong></p><p>The Data Controller is:</p><p><strong>Federico Toson</strong><br>Loc. Colle dei Cai<br>32042 Calalzo di Cadore (BL)<br>Italy</p><p>Email: <a href="mailto:info@lamaka.it">info@lamaka.it</a><br>Website: <a href="https://www.lamaka.it/">https://www.lamaka.it</a></p><p><strong>Types of data collected</strong></p><p>The website may collect personal data voluntarily provided by users, including:</p><ul><li><p>full name</p></li><li><p>email address</p></li><li><p>telephone number</p></li><li><p>information relating to booking requests or participation in experiences</p></li></ul><p>Technical data may also be collected while browsing, including:</p><ul><li><p>IP address</p></li><li><p>device and browser type</p></li><li><p>pages visited</p></li><li><p>time spent on the website</p></li><li><p>technical browsing data</p></li></ul><p><strong>Purposes of processing</strong></p><p>Data is processed to:</p><ul><li><p>respond to requests for information</p></li><li><p>manage booking requests or participation in activities</p></li><li><p>organise the experiences and services offered</p></li><li><p>improve the website browsing experience</p></li><li><p>comply with administrative and tax obligations</p></li><li><p>comply with legal requirements</p></li></ul><p><strong>Legal basis for processing</strong></p><p>Processing is based on:</p><ul><li><p>the user’s consent</p></li><li><p>steps taken at the user’s request before entering into a contract or making a booking</p></li><li><p>legal obligations</p></li><li><p>the Controller’s legitimate interest in managing the website and its activities</p></li></ul><p><strong>Processing methods</strong></p><p>Data is processed using appropriate technical and organisational measures designed to ensure:</p><ul><li><p>security</p></li><li><p>confidentiality</p></li><li><p>protection against unauthorised access</p></li><li><p>prevention of data loss or improper disclosure</p></li></ul><p><strong>Data retention</strong></p><p>Data is retained only for as long as strictly necessary for the purposes for which it was collected and, in any event, for no longer than 24 months, unless a longer period is required by law or for administrative, tax or legal reasons.</p><p><strong>Disclosure of data to third parties</strong></p><p>Data may be processed by technical service providers required for the operation of the website, including hosting, email and IT services connected with managing requests.</p><p>These parties process data in accordance with applicable law and, where necessary, are appointed as data processors.</p><p>Data is not disclosed publicly.</p><p><strong>Cookies and tracking technologies</strong></p><p>The website uses technical cookies required for its proper operation.</p><p>With the user’s consent, statistical or traffic-analysis tools may be used to improve the services offered.</p><p>Please see the Cookie Policy for further information.</p><p><strong>User rights</strong></p><p>Users may at any time:</p><ul><li><p>access their personal data</p></li><li><p>request its rectification</p></li><li><p>request its erasure</p></li><li><p>restrict processing</p></li><li><p>object to processing</p></li><li><p>request data portability</p></li></ul><p>Requests may be sent to: info@lamaka.it</p><p>These rights are granted under Articles 15–22 of Regulation (EU) 2016/679 (GDPR).</p><p><strong>Changes to this policy</strong></p><p>This Privacy Policy may be updated over time to reflect legal, technical or organisational changes.</p><p>Updated versions will be published on this page.</p><p><strong>Contact</strong></p><p>For any request concerning the processing of personal data: info@lamaka.it</p>';

        DB::table('legal_pages')->where('id', $page->id)->update([
            'translations' => json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    public function down(): void
    {
        // Keep any English text subsequently edited by the administrator.
    }
};
