<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskScheduler extends Model
{
    use HasFactory;
    protected $table = "task_schedulers";
    protected $fillable = [
        'name', 'details', 'time', 'status', 'repeats_per_day',
        'start_date', 'end_date', 'repeat_typeID', 'patient_id'
    ];
    public $timestamps = false;

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function customRepeats()
    {
        return $this->hasMany(CustomRepeat::class, 'repeat_typeID');
    }
    public function taskHistory()
    {
        return $this->hasMany(CustomRepeat::class, 'repeat_typeID');
    }
}
