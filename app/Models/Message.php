<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class Message extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    protected $encryptable = ['content'];

    protected $fillable = ['chat_id', 'user_id', 'content', "thing_id", 'read'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

}
