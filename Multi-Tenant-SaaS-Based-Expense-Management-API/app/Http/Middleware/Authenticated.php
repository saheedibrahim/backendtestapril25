<?php

namespace App\Http\Middleware;

use App\Models\Expense;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $expense = Expense::where('company_id', $user->company_id)->get();

        if (!$expense) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
