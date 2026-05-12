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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('is_admin');
            $table->boolean('must_set_password')->default(false)->after('is_super_admin');
            $table->string('setup_token')->nullable()->unique()->after('must_set_password');
            $table->timestamp('setup_token_expires_at')->nullable()->after('setup_token');
        });

        DB::table('users')
            ->where('email', 'macsandero@gmail.com')
            ->update([
                'is_admin' => true,
                'is_super_admin' => true,
                'must_set_password' => false,
                'setup_token' => null,
                'setup_token_expires_at' => null,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_super_admin',
                'must_set_password',
                'setup_token',
                'setup_token_expires_at',
            ]);
        });
    }
};
