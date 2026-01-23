<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'booking_id',
    ];

    // Relationship to Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
