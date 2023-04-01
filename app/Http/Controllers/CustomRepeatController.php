<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\CustomRepeat;
use App\Models\TaskScheduler;
use Illuminate\Database\Eloquent\Builder;

class CustomRepeatController extends Controller
{
    public static function checkCustomDays($days, $start_date)
    {
        foreach ($days as $day) {
            $d = date('Y-m-d', strtotime($day));
            while ($d < $start_date) {
                $d = date('Y-m-d', strtotime($d . ' + 7 days'));
            }
            $date[] = $d;
        }
        return $date;
    }

    public static function addCustomRepeats(TasksRequest $request, $task)
    {
        $days = CustomRepeatController::checkCustomDays($request->days, $request->strat_date);
        foreach ($days as $key => $date) {
            $day = CustomRepeat::create(['date' => $date, "task_id" => $task->id]);
        }
    }
    public  static function  getTodayCustomTasks($patient_id)
    {
        $data = [];
        $customRepeatsTasks = TaskScheduler::where('patient_id', $patient_id)->where('status', 1)
            ->whereHas('customRepeats', function (Builder $query) {
                $today = date('Y-m-d');
                $query->where('date', $today);
            })->get();
        foreach ($customRepeatsTasks as $task) {
            $data[] = $task;
        }
        return $data;
    }
    
}
