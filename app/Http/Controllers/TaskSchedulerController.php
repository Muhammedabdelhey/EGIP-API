<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\TaskScheduler;
use Illuminate\Http\Request;

class TaskSchedulerController extends Controller
{
    public function createTask(TasksRequest $request){
        $task =TaskScheduler::create([
            'name'=>$request->name,
            'details'=>$request->details,
            'time'=>$request->time,
            'status'=>true,
            'repeats_per_day'=>$request->repeats_per_day,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'repeat_typeID' => $request->repeat_typeID,
            'patient_id' => $request->patient_id
        ]);
        return responseJson(201, $task, "Task Inserted ");
    }
}
