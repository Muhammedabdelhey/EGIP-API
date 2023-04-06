<?php

namespace App\Repositories;

use App\Models\Patient;
use App\Repositories\Interfaces\PatientRepositoryInterface;

class PatientRepository implements PatientRepositoryInterface
{
    private Patient $patient;
    public function __construct(Patient $patient)
    {
            $this->patient=$patient;
    }
    public function addPatient(array $data)
    {
        return $this->patient->create($data);
    }

    public function getPatient($patientID)
    {
        return $this->patient->find($patientID);
    }


    // public function deletePatient($patientID)
    // {
    //     // TODO: Implement deletePatient() method.
    // }

    public function updatePatient($patientID, array $data)
    {
        return $this->patient->whereId($patientID)->update($data);
    }
}
