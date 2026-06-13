<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('role')) {
            $role = $request->session()->get('role');

            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'employee' => redirect()->route('employee.attendance'),
                default => redirect()->route('pin.show'),
            };
        }

        return $next($request);
    }
}
