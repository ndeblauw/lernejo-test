<?php

namespace App\Http\TenantFinder;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainAndSubdomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();
        $pos = strpos($host, config('multitenancy.landlord_domain'));

        // Landlord
        if (substr($host, 0, strlen(config('multitenancy.landlord_domain'))) === config('multitenancy.landlord_domain')) {
            ray("Detected LANDLORD for <{$host}>")->color('green');

            return null;
        }

        // Domain
        if ($pos === false) {
            $tenant = $this->getTenantModel()::whereDomain($host)->sole();
            session()->put('tenant_id', $tenant->id);
            ray("Detected TENANT, using DOMAIN - for <$host>: {$tenant->name}")->color('purple');

            return $tenant;
        }

        // Subdomain
        if (is_numeric($pos)) {
            $tenant = $this->getTenantModel()::whereSubdomain(substr($host, 0, $pos - 1))->first();

            if ($tenant === null) {
                ray("ERROR - subdomain <{$host}> does not exist")->color('red');
                abort(404, 'Tenant Not found');

                return null;
            }

            session()->put('tenant_id', $tenant->id);
            ray("Detected TENANT, using SUBDOMAIN - for <$host>: {$tenant->name}")->color('blue');

            return $tenant;
        }

        return null;
    }
}
