<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketActivity extends Model {
    protected $fillable = ['ticket_id', 'user_id', 'note', 'type'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}