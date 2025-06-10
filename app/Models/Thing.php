<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;

class Thing extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    protected $encryptable = [
        'imagesUrl',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'condition_id',
        'availability',
        'weight',
        'category_id',
        'material_id',
        'color_id',
        'location',
        'imagesUrl',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_things');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
