<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Job_Vacancy;
use App\Models\Resume;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]

class Job_Application extends Model
{
    public function user() {
        $this->belongsTo(User::class);
    }
    public function vacancy() {
        $this->belongsTo(Job_Vacancy::class);
    }
    public function resume() {
        $this->belongsTo(Resume::class);
    }
}
