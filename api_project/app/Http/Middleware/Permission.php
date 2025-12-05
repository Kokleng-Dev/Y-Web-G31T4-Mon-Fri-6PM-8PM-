<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$permission_key,$feature_key): Response
    {
        $auth = Auth::user();
        if(checkPermission($auth->role_id, $permission_key,$feature_key)){
            return $next($request);
        }
        return response()->json([
            'status' => 'unauthorized',
            'message' => 'You do not have permission to access this feature'
        ],401);
    }
}
