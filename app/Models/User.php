<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if (
                $user->getOriginal('type') === 'admin'
                && $user->getOriginal('email') === config('platform.protected_admin_email')
            ) {
                throw new \RuntimeException('Deleting the protected platform manager is not allowed.');
            }
        });

        static::updating(function (User $user) {
            $isProtected = $user->getOriginal('type') === 'admin'
                && $user->getOriginal('email') === config('platform.protected_admin_email');

            if (! $isProtected) {
                return;
            }

            if ($user->isDirty('email') || $user->isDirty('type')) {
                throw new \RuntimeException('Updating the protected platform manager email/type is not allowed.');
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->type === 'admin';
        }

        return false;
    }
}
