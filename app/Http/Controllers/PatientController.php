<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\Caregiver;
use App\Models\Patient;
use App\Models\User;
use App\Traits\ManageFileTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    use ManageFileTrait;
    public function addPatient(PatientRequest $request)
    {
        try {
            DB::beginTransaction();
            $auth = new AuthController;
            //0 is type for patient
            $user = $auth->register($request, 0);
            $photo = $this->uploadFile($request, 'photo', 'patientphoto');
            $patient = Patient::create([
                'Stage' => $request->Stage,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'photo' => $photo,
                'User_id' => $user->id,
                'gender' => $request->gender
            ]);
            DB::table('caregivers_paients')->insert([
                'caregiver_id' => $request->caregiver_id,
                'patient_id' => $patient->id
            ]);
            DB::commit();
            $data = patientData($user);
            return responseJson(201, $data, 'Patient successfully Added');
        } catch (Exception $e) {
            DB::rollback();
            return responseJson(401,"", $e);
        }
    }

    public function getPatients($caregiver_id)
    {
        $caregiver = Caregiver::find($caregiver_id);
        if ($caregiver) {
            if ($caregiver->patients->count() > 0) {
                $patients = $caregiver->patients()->get();
                foreach ($patients as $patient) {
                    $data[] = patientData($patient->user);
                }
                return responseJson(200, $data, 'All Patient for ' . $caregiver->user->name);
            }
            return responseJson(401, '', 'this caregiver not have Patients');
        }
        return responseJson(401, '', 'this caregiver_id not found');
    }

    public function getPatient($patient_id)
    {
        $patient = Patient::find($patient_id);
        if ($patient) {
            $data = patientData($patient->user);
            return responseJson(200, $data, 'data for patient');
        }
        return responseJson(401, '', 'this Pateint_id not found');
    }

    public function deletePatient($patient_id)
    {
        $patient = Patient::find($patient_id);
        if ($patient) {
            $this->deleteFile($patient->photo);
            User::destroy($patient->User_id);
            return responseJson(200, ' ', 'Patient deleted ');
        }
        return responseJson(401, '', 'this Pateint_id not found');
    }

    public function updatePatient(PatientRequest $request, $patient_id)
    {
        $patient = Patient::find($patient_id);
        $user = $patient->user;
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => Carbon::now()
        ]);
        $photo = $this->uploadFile($request, 'photo', 'patientphoto');
        if (!empty($photo)) {
            $this->deleteFile($patient->photo);
        } else {
            $photo = $patient->photo;
        }
        $patient->update([
            'Stage' => $request->Stage,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'photo' => $photo,
            'gender' => $request->gender
        ]);
        $data = patientData($user);
        return responseJson(201, $data, 'Patient Updated ');
    }

    public function getPatientImage($patient_id)
    {
        $patient = Patient::find($patient_id);
        if ($patient) {
            return $this->getFile($patient->photo);
        }
        return responseJson(401, '', 'this Pateint_id not found');
    }
}
