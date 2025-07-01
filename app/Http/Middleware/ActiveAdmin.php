<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if($user) {
            if (!$user->isAdmin() || ! $user->isActive()) return sendDontHavePermissionResponse();
        } else {
            return sendUnauthorizedResponse();
        }

        return $next($request);
    }
}
