<?php

namespace App\Models;

use App\Models\Concerns\TracksUserstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use SoftDeletes, TracksUserstamps;

    protected $fillable = [
        'business_name',
        'logo_path',
        'manager_name',
        'contact_person',
        'captcha_mode',
        'discord_webhook_url',
        'tagline',
        'about',
        'phone',
        'whatsapp',
        'email',
        'address',
        'weekday_hours',
        'weekend_hours',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function auditLabel(): string
    {
        return $this->business_name;
    }
}
