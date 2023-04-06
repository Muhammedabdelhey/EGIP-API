<?php

namespace App\Repositories;

use App\Models\Caregiver;
use App\Repositories\Interfaces\CaregiverRepositoryInterface;

class CaregiverRepository implements CaregiverRepositoryInterface
{
    public function addCaregiver(array $data)
    {
        return Caregiver::create($data);
    }
    public function getCaregiver($id){
        return Caregiver::find($id);
    }

}
