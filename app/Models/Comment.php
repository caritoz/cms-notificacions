<?php

namespace App\Models;

use App\Traits\HasMentions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory, HasMentions;

    const COMMENTABLE_MODELS = [Post::class];

    protected $fillable = ['user_id', 'parent_id', 'body', 'commentable_id', 'commentable_type'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
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
    public function commentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return $this->attributes['type'] = str_replace('App\\Models\\', '', $this->commentable_type);
    }

    /**
     * @return array
     */
    protected function threads(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->replies()->orderByDesc('updated_at')->get() ?? [],
        );
    }

    public function getMentionsAttribute()
    {
        return User::whereIn('id', $this->getMentions())->where('id', '!=', Auth::id())->get();
    }
}
