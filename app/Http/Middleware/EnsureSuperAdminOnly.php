<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuperAdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user  = Auth::user();
        if ($user->role->name !=='super_admin'){
            $respon['respon_status'] = array('status' => "ERROR.FORBIDDEN_ACCESS", 'code' =>  403, 'message' => "Anda tidak berhak mengakses sumber daya ini");
            return response()->json($respon, 403);
        }
        return $next($request);
    }
}
