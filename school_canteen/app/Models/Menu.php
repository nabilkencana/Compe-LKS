<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{OrderItem,Category};

class Menu extends Model
{
    protected $guarded =['id'];

    public function items()  {
          return $this->hasMany(OrderItem::class);
      }

    public function category()  {
          return $this->belongsTo(Category::class);
      }
    
}