<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use App\Models\{LaundryOrder};
#[Guarded(['id'])]
class LaundryService extends Model
{
    public function orders() {
        return $this->hasMany(LaundryOrder::class);
    }
}
