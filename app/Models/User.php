<?php

namespace App\Models;
use Illuminate\Support\Str;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Import HasApiTokens
use Filament\Panel; // Import Panel

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'company_reg_id', 'company_name', 'place', 'address',
        'phone', 'status', 'password', 'user_type',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->company_registration_id)) {
                $company->company_reg_id = (string) Str::uuid();
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // This is where you define who can access the Filament panel.
        // For example, only users with 'admin' email:
        // return str_ends_with($this->email, '@yourdomain.com');

        // For now, let's allow all authenticated users to access the panel
        return true;
    }

    public function clientPCs()
    {
        return $this->hasMany(NumberOfClientPC::class, 'company_id','id');
    }
}
