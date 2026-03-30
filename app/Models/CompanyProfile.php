<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'business_name',
        'tagline',
        'about',
        'phone',
        'whatsapp',
        'email',
        'address',
        'weekday_hours',
        'weekend_hours',
    ];
}
