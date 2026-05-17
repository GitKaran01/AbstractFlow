<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AbstractorController extends Controller {
 public function getMyTasks() {
    // Auth guard provides the logged-in user id instantly
    $tasks = Ticket::where('assigned_user_id', auth()->id())
                   ->with('activities') // Fetch GitHub-style timeline along with it
                   ->orderBy('due_date', 'asc')
                   ->get();

    return response()->json([
        'status' => 'success',
        'count' => $tasks->count(),
        'data' => $tasks
    ], 200);
}

    public function updateStatus(Request $request, $id) {
        $request->validate(['status' => 'required|in:in_progress']);
        $ticket = Ticket::where('id', $id)->where('assigned_user_id', auth()->id())->firstOrFail();

        $ticket->update(['status' => $request->status]);

        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'note' => 'Abstractor marked ticket status as In-Progress.',
            'type' => 'system'
        ]);

        return response()->json(['message' => 'Status updated to In-Progress.']);
    }

    public function logActivity(Request $request, $id) {
        $request->validate(['note' => 'required|string|max:255']);
        $ticket = Ticket::where('id', $id)->where('assigned_user_id', auth()->id())->firstOrFail();

        $activity = $ticket->activities()->create([
            'user_id' => auth()->id(),
            'note' => $request->note,
            'type' => 'manual' // GitHub style check-in note
        ]);

        return response()->json($activity, 201);
    }

 public function escalateTask(Request $request, $id) {
    $request->validate([
        'reason_category' => 'required|string', // e.g., "Technical Issue", "Property Dispute"
        'note' => 'required|string'
    ]);

    $ticket = Ticket::where('id', $id)->where('assigned_user_id', auth()->id())->firstOrFail();
    
    // Look up if this file has an explicitly designated backup abstractor mapped from your sheet
    $backupText = "No backup assigned.";
    if ($ticket->backup_user_id) {
        $backupUser = \App\Models\User::find($ticket->backup_user_id);
        if ($backupUser) {
            $backupText = "Designated backup vendor available: {$backupUser->name} ({$backupUser->vendor_id}).";
        }
    }

    // Change status to stalled so admin can intervene
    $ticket->update(['status' => 'stalled']);

    // Log the event onto your GitHub style timeline feed
    $ticket->activities()->create([
        'user_id' => auth()->id(),
        'note' => "🚨 WORK HALTED by Abstractor. Reason: [{$request->reason_category}] - {$request->note}. ({$backupText})",
        'type' => 'system'
    ]);

    return response()->json([
        'message' => 'Task halted successfully. Admin notified for backup swap assignment.',
        'backup_available' => $ticket->backup_user_id ? true : false
    ]);
}

    public function submitFinalReport(Request $request, $id) {
        // Validation enforces exactly PDF file format and a strict 15MB limit (15360 KB)
        $request->validate([
            'search_date' => 'required|date',
            'final_report' => 'required|file|mimes:pdf|max:15360',
            'notes' => 'nullable|string'
        ]);

        $ticket = Ticket::where('id', $id)->where('assigned_user_id', auth()->id())->firstOrFail();

        if ($request->hasFile('final_report')) {
            $fileName = 'ticket_' . $ticket->id . '_' . time() . '.pdf';
            $path = $request->file('final_report')->storeAs('reports', $fileName, 'public');

            $ticket->update([
                'pdf_path' => $path,
                'search_date' => $request->search_date,
                'status' => 'submitted_qc'
            ]);

            $ticket->activities()->create([
                'user_id' => auth()->id(),
                'note' => 'Uploaded PDF report and submitted file to Admin for QC check. Optional Note: ' . $request->notes,
                'type' => 'system'
            ]);

            return response()->json(['message' => 'PDF successfully submitted for QC review.']);
        }
    }
}