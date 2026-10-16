<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use App\Models\User;
use App\Models\Recipe;

#[Guarded(['id'])]
class Comment extends Model
{
    public function user(){
        return $this->belongsTo(User::class);        
    }

    public function recipe(){
        return $this->belongsTo(Recipe::class);        
    }
}
