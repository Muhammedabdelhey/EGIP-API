<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\CustomRepeat;
use App\Models\TaskScheduler;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomRepeatController;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class TaskSchedulerController extends Controller
{
    public function createTask(TasksRequest $request)
    {
        try {
            DB::beginTransaction();
            $task = TaskScheduler::create([
                'name' => $request->name,
                'details' => $request->details,
                'time' => $request->time,
                'status' => true,
                'repeats_per_day' => $request->repeats_per_day,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'repeat_typeID' => $request->repeat_typeID,
                'patient_id' => $request->patient_id
            ]);
            if ($request->repeat_typeID == 3) {
                CustomRepeatController::addCustomRepeats($request, $task);
            }
            DB::commit();
            return responseJson(201, taskData($task), "Task Inserted ");
        } catch (Exception $e) {
            DB::rollBack();
            return responseJson(401,'',$e);
        }
    }
}
