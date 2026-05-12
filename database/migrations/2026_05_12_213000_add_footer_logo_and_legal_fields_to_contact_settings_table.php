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
            $table->string('footer_logo')->nullable()->after('business_name');
            $table->string('company_name')->nullable()->after('legal_text');
            $table->string('tax_code')->nullable()->after('company_name');
            $table->string('vat_number')->nullable()->after('tax_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_settings', function (Blueprint $table) {
            $table->dropColumn([
                'footer_logo',
                'company_name',
                'tax_code',
                'vat_number',
            ]);
        });
    }
};
