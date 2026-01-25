<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class Guest extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'document_number',
        'birth_date',
    ];
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

}
