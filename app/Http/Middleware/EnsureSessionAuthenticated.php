<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->session()->get('role');
        $employeeId = $request->session()->get('employee_id');

        if ($role === 'employee' && ! $employeeId) {
            return redirect()->route('pin.show');
        }

        if (! in_array($role, ['admin', 'employee'], true)) {
            return redirect()->route('pin.show');
        }

        return $next($request);
    }
}
