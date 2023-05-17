<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('Estoy pasando por el IsAdmin middleware');

        if (auth()->user()->is_admin === 0) {
            return response()->json(
                [
                    "success" => true,
                    "message" => "You dont have pemissions"
                ],
                401
            );
        }
        
        return $next($request);
    }
}
