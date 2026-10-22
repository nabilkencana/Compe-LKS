<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\Schedule;

class Branch extends Model
{
     protected $guarded = ['id'];

       public function getRouteKeyName() : string {
        return 'slug';
    }

      public function room()  {
        return $this->hasMany(Room::class );
    }
    
  
}
