<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory,  Notifiable;
    public $timestamps = false;
    protected $fillable = [
        'Stage',
        'address',
        'birth_date',
        'User_id',
        'phone',
        'photo',
        'gender'
    ];
    protected $hidden = ['pivot', "user", "User_id"];

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
    public function caregivers()
    {
        return $this->belongsToMany(caregiver::class, 'caregivers_paients');
    }
    public function memories()
    {
        return $this->hasMany(MemoryLibrary::class);
    }
    public function taskScheduler()
    {
        return $this->hasMany(TaskScheduler::class);
    }
}
