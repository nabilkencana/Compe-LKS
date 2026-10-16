<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Seeker_Profile;
use App\Models\Resume;
use App\Models\Job_Vacancy;
use App\Models\Job_Application;

#[Fillable(['name', 'email', 'password','role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable , HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile() {
        return $this->hasOne(Seeker_Profile::class,'user_id');
    }
    public function resume() {
        return $this->hasMany(Resume::class);
    }
    public function job_vacancy() {
        return $this->hasMany(Job_Vacancy::class,'created_by');
    }
    public function job_application() {
        return $this->hasMany(Job_Application::class);
    }




}
