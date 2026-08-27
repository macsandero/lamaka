<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggDailyProduction extends Model
{
    use HasFactory;

    protected $fillable = ['production_date', 'quantity'];

    protected function casts(): array
    {
        return ['production_date' => 'date', 'quantity' => 'integer'];
    }
}
