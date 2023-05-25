<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ExtractPatientIdFromRelation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $modelName, $modelID)
    {
        $Id = $request->route($modelID);
        $patientID = app($modelName)->whereId($Id)->pluck('patient_id');
        $request->merge(['patient_id' => $patientID[0]]);
        // print($request->patient_id);
        //die;
        return $next($request);
    }
}
