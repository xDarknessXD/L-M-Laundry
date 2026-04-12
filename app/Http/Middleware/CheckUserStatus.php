<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->isPending()) {
                auth()->logout();
                return redirect()->route('waiting');
            }

            if ($user->isSuspended()) {
                auth()->logout();
                $request->session()->invalidate();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been suspended. Please contact the administrator.']);
            }
        }

        return $next($request);
    }
}
