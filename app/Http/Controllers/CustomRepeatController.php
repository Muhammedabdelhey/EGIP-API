<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\CustomRepeat;
use Illuminate\Http\Request;

class CustomRepeatController extends Controller
{
    public static function addCustomRepeats(TasksRequest $request,$task){
        foreach ($request->days as $day) {
            $days[] = date('Y-m-d', strtotime("next " . $day));
        }
        foreach ($days as $key => $date) {
            $day = CustomRepeat::create(['date' => $date, "task_id" => $task->id]);
        }
    }
}
