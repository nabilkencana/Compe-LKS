<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Ticket,User};

class TicketHistory extends Model
{
    protected $guarded = ['id'];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
    public function User() {
        return $this->belongsTo(User::class);
    }
}
