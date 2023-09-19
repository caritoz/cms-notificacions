<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationTypes extends Model
{
    use HasFactory;

    /**
     * Specific delivery channels
     * @Reference: https://laravel.com/docs/10.x/notifications#specifying-delivery-channels
     */
    const TYPE_CHANNELS = ['mail', 'broadcast', 'database', 'nexmo', 'slack'];
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
        'class',
        'order',
        'created_at',
        'updated_at',
    ];

    /**
     * @return NotificationTypes[]|Collection
     */
    public static function getAllReduced(): ?array
    {
        return NotificationTypes::all()
            ->groupBy('group')
            ->toArray();
    }

    /**
     * @return HasMany
     */
    public function notificationSettings(): HasMany
    {
        return $this->hasMany(NotificationSettings::class);
    }
}
