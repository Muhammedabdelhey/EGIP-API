<?php

//you should put your file on autoload array on composer.jeson file
// and run  composer dumpautoload on cmd

use App\Models\RepeatType;

if (!function_exists('responseJson')) {
    function responseJson($status, $data, $msg)
    {
        return response()->json(
            [
                'status' => $status,
                'data' => $data,
                'msg' => $msg
            ]
        );
    }
}
//now you can call this function anywhere you want
if (!function_exists('caregiverData')) {
    function caregiverData($user)
    {
        return $data = [
            'id' => $user->caregiver->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->type,
            'role' => $user->caregiver->Role,
        ];
    }
}
if (!function_exists('patientData')) {
    function patientData($user)
    {
        $photo = null;
        if ($user->patient->photo !== null) {
            $photo = "http://127.0.0.1:8000/api/patientphoto/" . $user->patient->id;
        }
        return $data = [
            'id' => $user->patient->id,
            'name' => $user->name,
            'email' => $user->email,
            'Stage' => $user->patient->Stage,
            'address' => $user->patient->address,
            'birth_date' => $user->patient->birth_date,
            'phone' => $user->patient->phone,
            'photo' => $photo,
            'type' => $user->type,
            'gender' => $user->patient->gender,
        ];
    }
}

if (!function_exists('MemoryData')) {
    function MemoryData($memory)
    {
        $photo = null;
        if ($memory->photo !== null) {
            $photo = "http://127.0.0.1:8000/api/memoryphoto/" . $memory->id;
        }
        return $data = [
            'id' => $memory->id,
            'name' => $memory->name,
            'description' => $memory->description,
            'type' => $memory->type,
            'photo' => $photo,
        ];
    }
}

if (!function_exists('TaskData')) {
    function taskData($task)
    {

        $type = RepeatType::where('id', $task->repeat_typeID)->pluck('type');

        $data = [
            'id' => $task->id,
            'name' => $task->name,
            'details' => $task->details,
            'time' => $task->time,
            'status' => $task->status,
            'repeats_per_day' => $task->repeats_per_day,
            'Start_date' => $task->start_date,
            'End_date' => $task->end_date,
            'Repeat Type' => $type[0],
            'patient_id' => $task->patient_id,
        ];
        if ($task->repeat_typeID == 3) {
            foreach ($task->customRepeats as $key) {
                $data['date']=$key->date;
                $d[]=$data;
            }
            $data=$d;
        }
        return $data;
    }
}
if (!function_exists('checkValdation')) {
    function checkValdation($validator)
    {
        if ($validator->fails()) {
            return responseJson(401, '', $validator->errors());
        }
        return true;
    }
}
