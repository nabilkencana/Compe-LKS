<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Room;

class Schedule extends Model
{
     protected $guarded = ['id'];

     public function schedule()  {
        return $this->belongsTo(User::class, 'organizer_id' );
    }
     public function room()  {
        return $this->belongsTo(Room::class);
    }

}