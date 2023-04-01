<?php

namespace App\Console\Commands;

use App\Models\TaskScheduler;
use Illuminate\Console\Command;

class checkEndDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:checkEndDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = TaskScheduler::where('status', 1)->get();
        if ($tasks) {
            $tomorrow = date('Y-m-d', strtotime(' + 1 days'));
            foreach ($tasks as $task) {
                if ($task->end_date < $tomorrow) {
                    $task->status = 0;
                    $task->save();
                }
            }
        }
    }
}
