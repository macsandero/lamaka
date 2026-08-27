<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EggContact extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'phone'];

    public function orders(): HasMany
    {
        return $this->hasMany(EggOrder::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
