<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCaregiver
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
        $Caregiver = Auth::user()->caregiver;
        if (isset($Caregiver) && ($Caregiver->id == $request->caregiver_id || $Caregiver->id == $request->route("caregiver_id"))) {
            return $next($request);
        }
        return responseJson(401, '', 'UnAuthorized Action');
    }
}
