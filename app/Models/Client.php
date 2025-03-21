<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'company',
        'city',
        'country',
        'address',
        'email',
        'phone',
        'tva_number',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
