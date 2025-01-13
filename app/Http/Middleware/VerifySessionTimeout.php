<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifySessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $timeout = config('session.lifetime') * 60;

            if ($lastActivity && time() - $lastActivity > $timeout) {
                Auth::logout();
                $request->session()->flush();

                if ($request->ajax()) {
                    return response()->json(['message' => 'Session expired'], 401);
                }

                return redirect()->route('login')
                    ->with('message', 'Your session has expired');
            }

            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}
