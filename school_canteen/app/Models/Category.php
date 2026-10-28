<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class Category extends Model
{
    protected $guarded = ['id'];

    public function menu()  {
          return $this->hasMany(Menu::class);
      }
}
