<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\TaskScheduler;
use App\Http\Controllers\CustomRepeatController;
use App\Models\Patient;
use App\Models\TaskHistory;
use App\Traits\ManageFileTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskSchedulerController extends Controller
{
    use ManageFileTrait;
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
                return responseJson(201, $data, 'task Scheduler data');
            }
            return responseJson(401, '', 'this Patient Not have Any task Scheduler');
        }
        return responseJson(401, '', 'this patient_id not found');
    }

    public function getToDayTasks($patient_id)
    {
        $patient = Patient::find($patient_id);
        if (!$patient) {
            return responseJson(401, '', 'this Patient Not have Any task Scheduler');
        }
        $today = date('Y-m-d');
        $tasks = TaskScheduler::where('patient_id', $patient_id)
            ->where('status', 1)->whereRaw('"' . $today . '" between `start_date` and `end_date`')
            ->doesntHave('customRepeats')->get();
        $data = CustomRepeatController::getTodayCustomTasks($patient_id);
        foreach ($tasks as $task) {
            $data[] = $task;
        }
        $data = $this->checkRepeatsPerDays($data);
        return responseJson(201, $data, "today tasks for this patient");
    }

    public function deleteTask($id)
    {
        $task = TaskScheduler::find($id);
        $photos=$task->taskHistory;
        foreach($photos as $photo){
            if($photo->photo !=="Null"){
                $this->deleteFile($photo->photo);
            }
        }
        if ($task) {
            $task->delete();
            return responseJson(201, "", " task Deleted ");
        }
        return responseJson(401, "", "this TaskId not found");
    }

    public function confirmTask(Request $request)
    {
        $task = TaskScheduler::find($request->task_id);
        if ($task) {
            $photo = $this->uploadFile($request, 'photo', 'Task History Photos');
            $history = TaskHistory::create([
                'photo' => $photo,
                'task_id' => $request->task_id
            ]);
            if ($history) {
                return responseJson(201, $history, "task confirmed");
            }
            return responseJson(401, "", "An Error Occuerd ");
        }
        return responseJson(401, "", "this TaskId not found");
    }
    public function updateTask($id, TasksRequest $request)
    {
        try {
            DB::beginTransaction();
            $task = TaskScheduler::find($id);
            if ($task) {
                $task->update([
                    'name' => $request->name,
                    'details' => $request->details,
                    'time' => $request->time,
                    'status' => $request->status,
                    'repeats_per_day' => $request->repeats_per_day,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'repeat_typeID' => $request->repeat_typeID,
                ]);
                if ($request->repeat_typeID == 3) {
                    CustomRepeatController::updateCustomRepeats($request, $task);
                }
                DB::commit();
                return responseJson(201, taskData($task), 'task updated ');
            }
            return responseJson(401, "", "this TaskId not found");
        } catch (Exception $e) {
            DB::rollBack();
            return responseJson(401, 'jfdsnsd', $e);
        }
    }

    public function checkRepeatsPerDays($tasks)
    {
        $data = [];
        foreach ($tasks as $task) {
            $repeats = (int)$task->repeats_per_day;
            $task->time = date('Y-m-d H:i:s', strtotime($task->time));
            $data[] = taskData($task);
            for ($i = 0; $i < $repeats - 1; $i++) {
                $newtask = $task;
                $newtask->time = date('Y-m-d H:i:s', strtotime(' + ' . 24 / $repeats . ' hour', strtotime($newtask->time)));
                $data[] = taskData($newtask);
            }
        }
        return $data;
    }
}
