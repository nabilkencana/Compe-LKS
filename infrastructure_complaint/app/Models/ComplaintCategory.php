<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Complaint;

class ComplaintCategory extends Model
{
    protected $guarded = ['id'];

    public function complaint() {
        return $this->hasMany(Complaint::class);
    }
}
