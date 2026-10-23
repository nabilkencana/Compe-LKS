<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function assign(Request $r, Ticket $ticket, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $val = $r->validate([
            'assigned_to' => 'required|exists:users,id,role,staff',
            'priority' => 'required|in:low,medium,high',
            'notes' => 'sometimes|string'
        ],[
            'assigned_to.exists' => 'User doesn`t not exist or not staff role'
        ]);

        if ($complaint->status !== 'accepted') {
            return response()->json([
                'success' => false,
                'message' => 'Only Accepted complaints can be make ticket.'
            ], 409);
        }

        if ($complaint->ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket have a exists'
            ],409);
        }

        $ticket = Ticket::create([
            ...$val,
            'created_by' => $r->user()->id,
            'ticket_number' => 'MKT' . '-' . now()->format('Y') . '-' . mt_rand(1, 9999),
            'complaint_id' => $complaint->id,
            'status' => 'open'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket Created Successfully',
            'data' => $ticket
        ]);
    }

    public function start(Request $r , string $id) {
        $ticket = Ticket::with('complaint')->findOrfail($id);

        $r->validate([
            'note' => 'required|string'
        ]);

        if ($ticket->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'Only Open ticket can be start.'
            ],409);
        }

        if ($r->user()->id !== $ticket->assigned_to) {
            return response()->json([
                'success' => false,
                'message' => 'This staff not handle'
            ],403);
        }

        $ticket->update(['status' => 'in_progress']);

        return response()->json([
            'success' => true,
            'message' => 'Ticket moved to in_progress.',
            'data' => $ticket
        ]);
    }

    public function resolve(Request $r , string $id) {
        $ticket = Ticket::with('complaint')->findOrFail($id);

        $val = $r->validate([
            'resolution_notes' => 'required|string',
        ]);

        $ticket->update([
            ...$val,
            'status' => 'resolved',
            'resolved_at ' => now()
        ]);
        $ticket->history()->create([
            'user_id' => $r->user()->id,
            'ticket_id' => $ticket->id,
            'old_status' => 'in_progress',
            'new_status' => $ticket->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket resolved successfully.',
            'data' => $ticket
        ]);
    }

    public function all() {
        $ticket = Ticket::with('complaint')->get();

        return response()->json([
            'success' => true,
            'message' => 'Ticket Retrivied Successfully',
            'data' => $ticket,
        ]);
    }

    public function show(Request $r , string $id){
        $ticket = Ticket::findOrFail($id);

        if ($r->user()->role !== 'staff'&&$r->user()->role !== 'admin' && $ticket->created_by === $r->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ticket Retrivied Successfully',
            'data' => $ticket
        ]);
    }

    public function index(Request $r) {
        $ticket = Ticket::where('assigned_to' , $r->user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Ticket Retrivied Successfully',
            'data' => $ticket
        ]);
    }
}
