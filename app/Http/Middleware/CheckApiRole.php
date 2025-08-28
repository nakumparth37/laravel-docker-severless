<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use App\Models\User;

class CheckApiRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized User'], 401);
        }
        $roleIds = Role::whereIn('role_type', $roles)->pluck('id')->toArray();
        if (!$roleIds) {
            return response()->json(['error' => 'Role not found'], 404);
        }
        if (!in_array($request->user()->role_id, $roleIds)) {
            return response()->json(['error' => 'Unauthorized User! Not Allow to access admin rescore'], 403);
        }

        return $next($request);
    }
}
