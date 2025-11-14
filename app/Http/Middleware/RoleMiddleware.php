<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // التحقق من وجود دور المستخدم
        if (!$user->role) {
            return redirect()->route('dashboard')->with('error', 'غير مصرح لك بالوصول إلى هذه الصفحة.');
        }
        
        // التحقق من الصلاحيات
        $hasRole = in_array($user->role, $roles);
        
        if (!$hasRole) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة.');
        }
        
        return $next($request);
    }
}
