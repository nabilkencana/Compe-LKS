<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function histories(Request $r, string $id)
    {
        $histories = Ticket::findOrFail($id);

        $val = $r->validate([
            'note' => 'required|string'
        ]);

        $histories = TicketHistory::create($val);

        return response()->json([
            'success' => true,
            'message' => 'Progress note added successfully.',
            'data' => $histories
        ]);
    }

    public function show(Request $r, string $id)
    {
        $histories = TicketHistory::findOrFail($id);

        if ($r->user()->id === $histories->id) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not your own',
                'data' => $histories
            ],401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket history retrivied successfully',
            'data' => $histories
        ]);
    }
}
