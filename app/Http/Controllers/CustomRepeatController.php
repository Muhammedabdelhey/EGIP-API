<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\CustomRepeat;
use Illuminate\Http\Request;

class CustomRepeatController extends Controller
{
    public static function addCustomRepeats(TasksRequest $request,$task){
        foreach ($request->days as $day) {
            $d=date('Y-m-d', strtotime("next " . $day));
            while($d<$request->start_date){
                $d=date('Y-m-d', strtotime($d. ' + 7 days'));
            }
            $days[] = $d;
        }
        foreach ($days as $key => $date) {
            $day = CustomRepeat::create(['date' => $date, "task_id" => $task->id]);
        }
    }
}
