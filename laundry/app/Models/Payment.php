<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{LaundryOrder,User};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class Payment extends Model
{
    public function user() {
        return $this->belongsTo(User::class,'changed_by');
    }
    public function orders() {
        return $this->belongsTo(LaundryOrder::class);
    }
}
