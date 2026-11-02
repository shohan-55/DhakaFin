<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'type',
        'name',
        'email',
        'organisation',
        'service',
        'preferred_date',
        'message',
    ];
}
