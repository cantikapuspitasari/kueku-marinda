<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses untuk halaman ini.');
        }

        // Akun yang dinonaktifkan Owner langsung kehilangan akses, walau sesinya masih hidup
        if ($user->status_akun === 'NONAKTIF') {
            Auth::guard('web')->logout();

            return redirect()->route('staff.login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan']);
        }

        return $next($request);
    }
}