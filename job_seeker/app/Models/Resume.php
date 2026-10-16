<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Job_Application;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]

class Resume extends Model
{
    public function user() {
        $this->belongsTo(User::class);
    }
    public function job_application() {
        $this->hasMany(Job_Application::class);
    }
}
