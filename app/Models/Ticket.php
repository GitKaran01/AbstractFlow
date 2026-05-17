<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
protected $fillable = [
    'order_id', 'client_name', 'loan_number', 'product_type', 'property_address', 
    'city', 'state', 'county', 'parcel_id', 'borrower_name', 'co_borrower_name', 
    'search_rate', 'copy_rate', 'total_cost', 'assigned_user_id', 'backup_user_id', 
    'status', 'search_date', 'pdf_path', 'qc_notes', 'due_date'
];

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
public function activities() {
    // Yeh ticket id ko ticket_activities table se map karega
    return $this->hasMany(\App\Models\TicketActivity::class, 'ticket_id')->orderBy('created_at', 'desc');
}
}