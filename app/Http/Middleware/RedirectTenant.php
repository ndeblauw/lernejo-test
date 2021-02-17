<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class RedirectTenant
{
    use UsesTenantModel;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $tenant = $this->getTenantModel()::checkCurrent()) {
            return $next($request);
        }

        $tenant = app('currentTenant');
        if ($tenant->use_domain && $request->getHost() !== $tenant->domain) {
            return redirect("http://{$tenant->domain}");
        }

        return $next($request);
    }
}
