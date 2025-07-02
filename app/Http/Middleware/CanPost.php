<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanPost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if(! $user) return sendUnauthorizedResponse();

        if(in_array($user->role, [UserRoles::ADMIN->value, UserRoles::WRITER->value]) && $user->isActive()) return $next($request);

        return sendDontHavePermissionResponse();
    }
}
