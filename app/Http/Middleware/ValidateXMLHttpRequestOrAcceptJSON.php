<?php

namespace App\Http\Middleware;

use Closure;

class ValidateXMLHttpRequestOrAcceptJSON
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return $next($request);
        }

        return response()->json(['`X-Requested-With with` value `XMLHttpRequest` or `Accept` with value `application/json` header must be present'], 400);
    }
}
