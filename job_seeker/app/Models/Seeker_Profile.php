<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class Seeker_Profile extends Model
{
    protected $table = 'seeker_profiles';

    public function user() {
        $this->belongsTo(User::class,'user_id');
    }
}
