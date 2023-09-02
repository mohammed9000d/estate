<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\NoPrimaryDomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

/**
 * @property-read string $plan_name The tenant's subscription plan name
 * @property-read bool $on_active_subscription Is the tenant actively subscribed (not on grace period)
 * @property-read bool $can_use_app Can the tenant use the application (is on trial or subscription)
 *
 * @property-read Domain[]|Collection $domains
 */

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, Notifiable;

    public $incrementing = false;
    public $token = null;

    public function primary_domain(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Domain::class)->where('is_primary', true);
    }

    public function fallback_domain(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Domain::class)->where('is_fallback', true);
    }

    // function to generate id

    public function route($route, $parameters = [], $absolute = true): array|string
    {
        dd(123);
        if (!$this->primary_domain) {
            throw new NoPrimaryDomainException;
        }

        $domain = $this->primary_domain->domain;

        $parts = explode('.', $domain);
        if (count($parts) === 1) { // If subdomain
            $domain = Domain::domainFromSubdomain($domain);
        }

        return tenant_route($domain, $route, $parameters, $absolute);
    }

    public static function generateId(): int
    {
        $id = rand(111111111, 999999999);
        if (Tenant::where('id', $id)->exists()) {
            return Tenant::generateId();
        }
        return $id;
    }
}
