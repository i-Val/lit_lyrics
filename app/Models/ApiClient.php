<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'api_key_hash',
        'is_active',
        'subscription_plan',
        'subscription_expires_at',
        'last_used_at',
        'last_used_ip',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'api_key_hash',
    ];

    public static function generateApiKey(): string
    {
        return 'll_'.Str::random(64);
    }

    public static function hashApiKey(string $apiKey): string
    {
        return hash_hmac('sha256', $apiKey, (string) config('app.key', ''));
    }

    public function isSubscriptionActive(): bool
    {
        if ($this->subscription_expires_at === null) {
            return false;
        }

        return $this->subscription_expires_at->isFuture();
    }
}
