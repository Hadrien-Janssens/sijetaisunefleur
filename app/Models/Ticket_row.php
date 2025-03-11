<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket_row extends Model
{
    /** @use HasFactory<\Database\Factories\TicketRowFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'quantity', 'price'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
