<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{TicketHistory,User,Complaint};
class Ticket extends Model
{
    protected $guarded = ['id'];

    public function history() {
        return $this->hasMany(TicketHistory::class);
    }
    public function complaint() {
        return $this->belongsTo(Complaint::class);
    }
    public function user() {
        return $this->belongsTo(User::class,'assigned_to', 'created_by');
    }
}
