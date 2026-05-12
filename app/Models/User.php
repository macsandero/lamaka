<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_super_admin',
        'must_set_password',
        'setup_token',
        'setup_token_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'must_set_password' => 'boolean',
            'setup_token_expires_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin || $this->is_super_admin;
    }

    public function issuePasswordSetupToken(): string
    {
        $this->forceFill([
            'setup_token' => Str::random(64),
            'setup_token_expires_at' => now()->addDays(7),
            'must_set_password' => true,
        ])->save();

        return $this->setup_token;
    }

    public function canUsePasswordSetupToken(): bool
    {
        return $this->must_set_password
            && filled($this->setup_token)
            && $this->setup_token_expires_at?->isFuture();
    }
}
