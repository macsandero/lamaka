<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EggSaleSetting extends Model
{
    protected $fillable = ['unit_price'];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2'];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], ['unit_price' => 0.40]);
    }
}
