<?php

namespace App\Models;

use App\Notifications\sendInternalConfirmationEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class Settings extends Model
{
    use SoftDeletes, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_email',
    ];

    /**
     * Update default recipient email field from `$this->email` to `$this->contact_email`
     */
    public function routeNotificationForMail(Notification $notification): string
    {
        return $this->contact_email;
    }

    public static function sendBookingConfirmation($booking)
    {
        $settings = Settings::first();
        $settings->notify(new sendInternalConfirmationEmail($booking));
    }
}
