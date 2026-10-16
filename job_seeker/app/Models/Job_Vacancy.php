<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Job_Application;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]

class Job_Vacancy extends Model
{
protected $table = 'job_vacancies';

    public function user() {
        $this->belongsTo(User::class,'created_by');
    }

     public function job_application() {
        $this->hasMany(Job_Application::class);
    }
}
