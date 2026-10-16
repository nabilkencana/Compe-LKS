<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use App\Models\Recipe;

#[Guarded(['id'])]
class Category extends Model
{
     public function rating(){
        return $this->hasMany(Recipe::class);        
    }
}
