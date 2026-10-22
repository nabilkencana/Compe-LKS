<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

class Room extends Model
{
     protected $guarded = ['id'];

     public function branch()  {
        return $this->belongsTo(Branch::class);
    }

      public function schedule()  {
        return $this->hasMany(Schedule::class );
    }

}
