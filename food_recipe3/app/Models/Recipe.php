<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use App\Models\User;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\Category;


#[Guarded(['id'])]
class Recipe extends Model
{
    public function user(){
        return $this->belongsTo(User::class);        
    }
    public function category(){
        return $this->belongsTo(Category::class);        
    }
    
    public function rating(){
        return $this->hasMany(Rating::class);        
    }
    public function comment(){
        return $this->hasMany(Comment::class);        
    }
}
