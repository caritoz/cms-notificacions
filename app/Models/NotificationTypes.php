<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTypes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'group', 'class', 'order'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'class', 'order', 'created_at', 'updated_at',
    ];

    /**
     * Specific delivery channels
     * @Reference: https://laravel.com/docs/10.x/notifications#specifying-delivery-channels
     */
    const TYPE_CHANNELS = ['mail', 'broadcast', 'database', 'nexmo', 'slack'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notificationSettings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotificationSettings::class);
    }

    /**
     * @return NotificationTypes[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAllReduced(): \Illuminate\Database\Eloquent\Collection|array
    {
        return NotificationTypes::all()
            ->groupBy('group')
            ->toArray();
    }
}
