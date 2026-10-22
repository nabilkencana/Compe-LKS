<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Complaint;
class Room extends Model
{
    protected $guarded = ['id'];

    public function complaint() {
        return $this->hasMany(Complaint::class);
    }
}
