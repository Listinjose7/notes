<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use App\Models\User;


class UserProfile extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
    ];

    // Define the relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
