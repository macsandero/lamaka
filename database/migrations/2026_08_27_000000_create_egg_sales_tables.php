<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('egg_contacts', function (Blueprint $table): void {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->timestamps();
            $table->index(['last_name', 'first_name']);
        });

        Schema::create('egg_sale_settings', function (Blueprint $table): void {
            $table->id();
            $table->decimal('unit_price', 8, 2)->default(0.40);
            $table->timestamps();
        });

        Schema::create('egg_orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('egg_contact_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('order_date');
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_price', 10, 2);
            $table->boolean('is_collected')->default(false);
            $table->timestamp('collected_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['order_date', 'is_collected']);
        });

        Schema::create('egg_daily_productions', function (Blueprint $table): void {
            $table->id();
            $table->date('production_date')->unique();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();
        });

        DB::table('egg_sale_settings')->insert([
            'unit_price' => 0.40,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('egg_daily_productions');
        Schema::dropIfExists('egg_orders');
        Schema::dropIfExists('egg_sale_settings');
        Schema::dropIfExists('egg_contacts');
    }
};
