<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Filament\Models\Contracts\FilamentUser; // ✅ ADD
use Filament\Panel;

class User extends Authenticatable implements FilamentUser // ✅ IMPLEMENT
{
    use HasFactory, Notifiable, HasApiTokens;

    protected static function booted(): void
    {
        // A super admin can never be deleted, regardless of the path
        // (Filament row/bulk delete, company cascade or API).
        static::deleting(function (User $user) {
            if ($user->user_type === 'super_admin') {
                return false;
            }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'status',
        'password',
        'user_type',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // allow all authenticated users
    }
}
