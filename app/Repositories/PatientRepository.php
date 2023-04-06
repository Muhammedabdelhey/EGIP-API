<?php

namespace App\Repositories;

use App\Models\Patient;
use App\Repositories\Interfaces\PatientRepositoryInterface;

class PatientRepository implements PatientRepositoryInterface
{

    public function addPatient(array $data)
    {
        return Patient::create($data);
    }

    public function getPatient($patientID)
    {
        return Patient::find($patientID);
    }

    // public function getPatients($caregiverID)
    // {
    //     // TODO: Implement getPatients() method.
    // }

    // public function deletePatient($patientID)
    // {
    //     // TODO: Implement deletePatient() method.
    // }

    public function updatePatient($patientID, array $data)
    {
        return Patient::whereId($patientID)->update($data);
    }
}
