<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaregiverRequest;
use App\Models\Caregiver;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CaregiverController extends Controller
{

    public  function addCaregiver(CaregiverRequest $request)
    {
        try {
            DB::beginTransaction();
            $auth = new AuthController;
            //1 is type for caregiver
            $user = $auth->register($request, 1);
            Caregiver::create([
                'Role' => $request->Role,
                'User_id' => $user->id,
            ]);
            DB::commit();
            $data = caregiverData($user);
            return responseJson(201, $data, 'Caregiver successfully registered');
        } catch (Exception $e) {
            DB::rollback();
            return responseJson(401, "", $e);
        }
    }
}
