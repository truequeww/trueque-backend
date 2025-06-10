<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferThing extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'thing_id',
        'is_offered',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }
}
