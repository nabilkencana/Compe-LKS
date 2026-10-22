<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{Ticket,User,Room,ComplaintCategory};
class Complaint extends Model
{
    protected $guarded = ['id'];

    public function ticket() {
        return $this->hasOne(Ticket::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function room() {
        return $this->belongsTo(Room::class);
    }
    public function category() {
            return $this->belongsTo(ComplaintCategory::class);
    }
}

