<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header("api-key")){
            return response()->json([
                "message" => "api key not found"
            ], 401);
        }
        if($request->header("api-key") != '123' && $request->header("api-key") != '456'){
            return response()->json([
                "message" => "invalid api key"
            ]);
        }
        if ($request->header("api-key") == '123'){
            return $next($request);
        }
        if ($request->header("api-key") == '456'){
            return response()->json([
                "message" => "no permission to access from mobile app"
            ], 401);
        }
        // return $next($request);
    }
}
