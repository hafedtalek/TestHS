<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'firstname', 'lastname', 'email', 'phone', 'comment'
    ];
}
