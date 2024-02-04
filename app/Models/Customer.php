<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class Customer extends Model
{
    use SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email_address', 
        'phone_number',
    ];

    public function vehicle() :HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function booking() :HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Update default recipient email field from `$this->email` to `$this->email_address`
     */
    public function routeNotificationForMail(Notification $notification): string
    {
        return $this->email_address;
    }
}
