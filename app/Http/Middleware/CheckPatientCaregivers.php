<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPatientCaregivers
{
    public function __construct()
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, )
    {
        $loggedInCaregiver = Auth::user()->caregiver;
        if (!$loggedInCaregiver) {
            return responseJson(401, '', 'UnAuthorized Action 1');
        }
        // $patient_id = $request->route('patient_id');
        // if (!$patient_id) {
        //     $patient_id = $request->patient_id;
        // }
        $patient_id=getPatientId($request);
        $Caregiverpatients = collect($loggedInCaregiver->patients);
        $patientExists = $Caregiverpatients->contains(function ($patient) use ($patient_id) {
            return $patient->id == $patient_id;
        });
        if ($patientExists) {
            return $next($request);
        }

        return responseJson(401, '', 'UnAuthorized Action 2');

    }
}
