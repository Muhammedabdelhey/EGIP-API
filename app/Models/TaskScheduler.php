<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskScheduler extends Model
{
    use HasFactory;
    protected $table="task_schedulers";
    protected $fillable=['name','details','time','repeat','repeats_per_day','times_of_repeat','patient_id'];
    public $timestamps=false;
}
