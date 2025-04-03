<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'with_invoice',
        'is_sent',
        'is_paid',
        'client_id',
        'reference',
        'with_tva',
        'comment',
        'echeance',
        'remise',
        'remiseAmount',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function ticketRows()
    {
        return $this->hasMany(Ticket_row::class);
    }
}
