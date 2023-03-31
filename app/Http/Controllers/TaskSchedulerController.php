<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\TaskScheduler;
use App\Http\Controllers\CustomRepeatController;
use App\Models\CustomRepeat;
use App\Models\Patient;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
            return responseJson(401, '', $e);
        }
    }

    public function getTask($id)
    {
        $task = TaskScheduler::find($id);
        if ($task) {
            return responseJson(201, taskData($task), "done");
        }
        return responseJson(401, "", "this TaskId not found");
    }
    public function getAllTasks($patient_id)
    {
        $patient = Patient::find($patient_id);
        if ($patient) {
            if ($patient->taskScheduler->count() > 0) {
                $taskScheduler = $patient->taskScheduler;
                foreach ($taskScheduler as $task) {
                    $data[] = taskData($task);
                }
                return responseJson(200, $data, 'task Scheduler data');
            }
            return responseJson(401, '', 'this Patient Not have Any task Scheduler');
        }
        return responseJson(401, '', 'this patient_id not found');
    }

    public function getToDayTasks($patient_id)
    {
        $today = date('Y-m-d');
        $tasks = TaskScheduler::where('patient_id', $patient_id)
            ->where('status', 1)->whereRaw('"' . $today . '" between `start_date` and `end_date`')
            ->doesntHave('customRepeats')->get();
        $data = CustomRepeatController::getTodayCustomTasks($patient_id);
        foreach ($tasks as $task) {
            $data[] = taskData($task);
        }
        return responseJson(201, $data, "today tasks for this patient");
    }
}
