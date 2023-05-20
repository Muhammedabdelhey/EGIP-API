<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\PatientRepositoryInterface;
use App\Repositories\PatientRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPatientCaregivers
{
    public function __construct(private PatientRepositoryInterface $patientRepository)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $patient = $this->patientRepository->getPatient($request->route('patient_id'));
        $patientCaregivers = collect($patient->caregivers);
        $loggedInCaregiver = Auth::user()->caregiver;
        $loggedInCaregiverIsOnPatientCaregivers = $patientCaregivers->contains(function ($caregiver) use ($loggedInCaregiver) {
            return $caregiver->id === $loggedInCaregiver->id;
        });
        if ($loggedInCaregiverIsOnPatientCaregivers) {
            return $next($request);
        }
        return responseJson(401, '', 'UnAuthorized Action');
    }
}
