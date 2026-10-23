<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Ticket;

class ReportController extends Controller
{
    public function complaintSummary()
    {
        $summary = Complaint::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        return response()->json(['message' => 'Complaint Retrivied Successfully', 'data' => $summary]);
    }

    public function complaintByCategory()
    {
        $categories = Complaint::with('category:id,name')
            ->selectRaw('category_id, count(*) as total')
            ->groupBy('category_id')
            ->get();

        return response()->json(['message' => 'Complaint Retrivied Successfully', 'data' => $categories]);
    }

    public function complaintByRoom()
    {
        $rooms = Complaint::selectRaw('room_id, count(*) as total')
            ->groupBy('room_id')
            ->get();

        return response()->json(['message' => 'Complaint Retrivied Successfully', 'data' => $rooms]);
    }

    public function complaintTrends()
    {
        $trends = Complaint::selectRaw("strftime('%Y-%m', created_at) as month, count(*) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();


        return response()->json(['message' => 'Complaint Retrivied Successfully', 'data' => $trends]);
    }

    public function ticketResolution()
    {
        $resolutionTimes = Ticket::selectRaw("status,AVG((julianday([resolved_at ]) - julianday(created_at)) * 24) as avg_resolution_time")
            ->groupBy('status')
            ->get();

        return response()->json(['message' => 'Complaint Retrivied Successfully', 'data' => $resolutionTimes]);
    }
}
