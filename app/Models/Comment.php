<?php

    namespace App\Models;

    use App\Traits\HasMentions;
    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\MorphTo;
    use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;
    use HasMentions;

    const COMMENTABLE_MODELS = [Post::class];

    protected $fillable = ['user_id', 'parent_id', 'body', 'commentable_id', 'commentable_type'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Eager load by default
     *
     * @var string[]
     */
    protected $with = ['user', 'commentable'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type', 'threads', 'mentions'];

    /**
     * Get the parent commentable model (posts/).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return $this->attributes['type'] = str_replace('App\\Models\\', '', $this->commentable_type);
    }

    public function getMentionsAttribute()
    {
        return User::whereIn('id', $this->getMentions())->where('id', '!=', Auth::id())->get();
    }

    /**
     * @return array
     */
    protected function threads(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->replies()->orderByDesc('updated_at')->get() ?? [],
        );
    }

    /**
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
