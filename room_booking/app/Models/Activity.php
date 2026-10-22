<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Activity extends Model
{
    protected $guarded = ['id'];

     public function schedule()  {
        return $this->belongsTo(User::class, 'organizer_id' );
    }
}
    