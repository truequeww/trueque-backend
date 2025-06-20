<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class Notification extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    protected $encryptable = ['message'];

    protected $fillable = [
        'user_id',
        'message',
        'is_read',
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
