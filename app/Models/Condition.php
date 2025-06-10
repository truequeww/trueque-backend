<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Define the relationship with Things
    public function things()
    {
        return $this->hasMany(Thing::class);
    }
}
