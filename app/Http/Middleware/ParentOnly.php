<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isKid()) {
            abort(403, 'Tính năng này dành cho Ba Mẹ');
        }

        return $next($request);
    }
}
