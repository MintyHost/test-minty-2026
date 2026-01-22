<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'checkin_at',
        'checkout_at',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checkin_at' => 'datetime',
            'checkout_at' => 'datetime',
        ];
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

}
