<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPublicLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(in_array(session('locale'), ['it', 'en'], true) ? session('locale') : 'it');

        return $next($request);
    }
}
