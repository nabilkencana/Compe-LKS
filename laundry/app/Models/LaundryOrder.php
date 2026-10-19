<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{LaundryService,User,OrderStatusHistory};
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded(['id'])]
class LaundryOrder extends Model
{
     public function staff() {
        return $this->belongsTo(User::class, 'staff_id', 'id')->where('role', 'staff');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
     public function service() {
        return $this->belongsTo(LaundryService::class);
    }
     public function payments()  {
        return $this->hasMany(Payment::class);
    }
    public function histories() {
        return $this->hasMany(OrderStatusHistory::class,'changed_by');
    }
}
