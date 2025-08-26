<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Submit extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'request_status',
        'name',
        'mobile',
        'mobile_verified_at',
        'otp_code',
        'otp_expires_at',
    ];

    protected $casts = [
        'mobile_verified_at' => 'datetime',
    ];

    public function routeNotificationForPayamakYab(): ?string
    {
        return $this->mobile;
    }
}
