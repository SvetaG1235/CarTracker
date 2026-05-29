<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещён. Требуются права администратора.');
        }
        return $next($request);
    }
}