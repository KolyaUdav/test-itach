<?php

namespace App\Http\Middleware;

use App\Enums\Roles\Permissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasPermission
{
    /**
     * Проверит наличие прав на выполнение запроса
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $permId): Response
    {
        $role = $request->user()->getRole();

        if (!$role) {
            return response()->json([
                'message' => config('errors.role.not_exists'),
                'status' => 'error',
            ], 403);
        }

        try {
            $perm = Permissions::from($permId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => config('errors.role.perm_not_exists'),
            ], 400);
        }

        $roleHandler = $role->getHandlerInstance();
        $hasPerm = $roleHandler->hasPermission($perm);

        if (!$hasPerm) {
            return response()->json([
                'message' => config('errors.role.not_have'),
                'status' => 'error',
            ], 403);
        }

        return $next($request);
    }
}
