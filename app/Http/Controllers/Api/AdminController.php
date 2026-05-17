<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {
    public function createUser(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'abstractor'
        ]);

        return response()->json(['message' => 'Abstractor account created.', 'user' => $user], 201);
    }

  public function createTicket(Request $request) {
    $validated = $request->validate([
        'order_id' => 'required|string|unique:tickets',
        'client_name' => 'required|string',
        'loan_number' => 'required|string',
        'product_type' => 'required|string', // Added validation rule to match your test rows
        'property_address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'county' => 'required|string',
        'parcel_id' => 'required|string',
        'borrower_name' => 'required|string',
        'co_borrower_name' => 'nullable|string',
        'assigned_user_id' => 'nullable|exists:users,id',
        'due_date' => 'required|date'
    ]);

    if (!empty($validated['assigned_user_id'])) {
        $validated['status'] = 'assigned';
    }

    $ticket = Ticket::create($validated);

    if ($ticket->assigned_user_id) {
        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'note' => "Ticket manual setup complete. Initial assignment to abstractor ID: {$ticket->assigned_user_id}.",
            'type' => 'system'
        ]);
    }

    return response()->json(['message' => 'Ticket successfully created from Excel source data.', 'ticket' => $ticket], 201);
}

    public function reassignTicket(Request $request, $id) {
        $request->validate([
            'assigned_user_id' => 'required|exists:users,id',
            'reason' => 'required|string'
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldUser = $ticket->assignedUser ? $ticket->assignedUser->name : 'Unassigned';
        $newUser = User::findOrFail($request->assigned_user_id);

        $ticket->update([
            'assigned_user_id' => $request->assigned_user_id,
            'status' => 'assigned',
            'qc_notes' => $request->reason
        ]);

        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'note' => "Reassigned from {$oldUser} to {$newUser->name}. Reason: " . $request->reason,
            'type' => 'system'
        ]);

        return response()->json(['message' => 'Ticket successfully reassigned.']);
    }

    public function reviewTicket(Request $request, $id) {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string'
        ]);

        $ticket = Ticket::findOrFail($id);

        if ($request->action === 'approve') {
            $ticket->update(['status' => 'completed', 'qc_notes' => $request->notes]);
            $msg = "Ticket approved and completed.";
        } else {
            $ticket->update(['status' => 'assigned', 'qc_notes' => $request->notes]); // Sends back
            $msg = "Ticket rejected during QC check.";
        }

        $ticket->activities()->create([
            'user_id' => auth()->id(),
            'note' => $msg . " Notes: " . $request->notes,
            'type' => 'system'
        ]);

        return response()->json(['message' => $msg]);
    }
}