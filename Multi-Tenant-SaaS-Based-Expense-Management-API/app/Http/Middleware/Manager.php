<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Manager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if ($request->user()->hasRole($roles) != "Admin" || $request->user()->hasRole($roles) != "Manager") {
            return response()->json([
                'message' => 'Unauthorized User'
            ], 404);
        }

        return $next($request);
    }
}
