<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $page = DB::table('legal_pages')->where('slug', 'cookie-policy')->first();

        if (! $page) {
            return;
        }

        $translations = json_decode($page->translations ?? '{}', true) ?: [];
        $currentEnglishBody = data_get($translations, 'en.body');

        $cookiePolicy = '<p>Last updated: 12 May 2026</p><p>This Cookie Policy applies to citizens and lawful permanent residents of the European Economic Area (EEA) and Switzerland.</p><p><strong>1. Introduction</strong></p><p>The website <a href="https://lamaka.it">https://lamaka.it</a> uses cookies and similar technologies to ensure that the website operates correctly and to improve the user’s browsing experience.</p><p>This document describes the types of cookies used and how they can be managed.</p><p><strong>2. What are cookies?</strong></p><p>Cookies are small text files stored on the user’s device while browsing a website.</p><p>Cookies allow the website to recognise the user’s device and remember certain information relating to their browsing activity.</p><p><strong>3. Types of cookies used</strong></p><p><strong>3.1 Technical and functional cookies</strong></p><p>The website uses only technical cookies that are necessary for the proper operation of its pages and services.</p><p>These cookies allow, for example:</p><ul><li><p>the website to load correctly</p></li><li><p>browsing sessions to be managed</p></li><li><p>the user’s technical preferences to be retained</p></li><li><p>the website’s essential features to operate correctly</p></li></ul><p>These cookies do not require the user’s prior consent.</p><p><strong>3.2 Third-party cookies</strong></p><p>The website may contain links to external services or platforms, including:</p><ul><li><p>Instagram</p></li><li><p>Facebook</p></li><li><p>WhatsApp</p></li></ul><p>Any interaction with these services may involve the processing of personal data in accordance with their respective privacy policies.</p><p>LAMAKA does not directly control cookies that may be installed by external services.</p><p><strong>4. Managing and disabling cookies</strong></p><p>Users can manage or disable cookies directly through their browser settings.</p><p>Disabling certain technical cookies may prevent the website from operating correctly.</p><p><strong>5. User rights</strong></p><p>Users may exercise the rights granted under Regulation (EU) 2016/679 (GDPR), including:</p><ul><li><p>access to personal data</p></li><li><p>rectification of data</p></li><li><p>erasure of data</p></li><li><p>restriction of processing</p></li><li><p>objection to processing</p></li></ul><p>For any request, please contact: info@lamaka.it</p><p><strong>6. Contact details</strong></p><p><strong>Federico Toson</strong><br>Loc. Colle dei Cai<br>32042 Calalzo di Cadore (BL)<br>Italy</p><p>Email: <a href="mailto:info@lamaka.it">info@lamaka.it</a><br>Website: <a href="https://www.lamaka.it">https://www.lamaka.it</a></p>';

        $translations['en']['title'] = $translations['en']['title'] ?? 'Cookie Policy';

        if (blank($currentEnglishBody) || str_contains($currentEnglishBody, 'Enter the English Cookie Policy')) {
            $translations['en']['body'] = $cookiePolicy;
        }

        DB::table('legal_pages')->where('id', $page->id)->update([
            'translations' => json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }

    public function down(): void
    {
        // The Italian policy and any later manual English edits must remain untouched.
    }
};
