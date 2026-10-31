<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekPeran
{
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        $user = $request->user();
        if (! $user || ! in_array($user->peran, $peran, true)) {
            abort(403, 'Anda tidak memiliki hak mengakses halaman ini.');
        }
        return $next($request);
    }
}
