<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'from_user_id',
        'to_user_id',
        'cash_value_offered',
        'cash_value_requested',
        'status_id',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function status()
    {
        return $this->belongsTo(OfferStatus::class);
    }

    public function offerThings()
    {
        return $this->hasMany(OfferThing::class);
    }
}
