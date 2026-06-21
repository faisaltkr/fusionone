<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class License extends Model
{
    protected $fillable = [
        'company_id',
        'license_type',
        'unique_register_id',
        'app_id',
        'hardware_id',
        'expiry',
        'license_key',
        'support_expiry_date',
        'status',
        'activated_at',
    ];

    protected $casts = [
        'expiry' => 'datetime',
        'support_expiry_date' => 'date',
        'activated_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Resolve the expiry date for a license type.
     * demo => 30 days from now, full => 1 year from now.
     */
    public static function calculateExpiry(string $licenseType): Carbon
    {
        return $licenseType === 'full'
            ? now()->addYear()
            : now()->addDays(30);
    }

    /**
     * Build a deterministic, verifiable license key from the license
     * identity (unique_register_id + app_id + hardware_id + expiry),
     * signed with the application key.
     */
    public function generateLicenseKey(): string
    {
        $payload = implode('|', [
            $this->unique_register_id,
            $this->app_id,
            $this->hardware_id,
            optional($this->expiry)->toDateString(),
            $this->license_type,
        ]);

        $signature = strtoupper(hash_hmac('sha256', $payload, (string) config('app.key')));
        $core = substr(preg_replace('/[^A-Z0-9]/', '', $signature), 0, 20);

        $prefix = match ($this->app_id) {
            'fusionOne' => 'FONE',
            'R-Pos'     => 'RPOS',
            'Pos'       => 'POS',
            default     => 'LIC',
        };

        return $prefix . '-' . implode('-', str_split($core, 5));
    }

    /**
     * Verify a key matches what this license's identity should produce.
     */
    public function verifyLicenseKey(string $key): bool
    {
        return hash_equals($this->generateLicenseKey(), $key);
    }

    public function isExpired(): bool
    {
        return $this->expiry !== null && $this->expiry->isPast();
    }
}
