<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EggOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'egg_contact_id', 'created_by_user_id', 'order_date', 'quantity', 'unit_price', 'total_price',
        'is_collected', 'collected_at', 'cancelled_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'is_collected' => 'boolean',
            'collected_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(EggContact::class, 'egg_contact_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function getCreatorInitialAttribute(): ?string
    {
        return match (strtolower((string) $this->creator?->email)) {
            'macsandero@gmail.com' => 'S',
            'vera.munzi@gmail.com' => 'V',
            'federicotoson07@gmail.com' => 'F',
            default => null,
        };
    }
}
