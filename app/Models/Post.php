<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'body', 'user_id'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
//    protected $with = ['comments'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id')->orderBy('created_at');
    }

    // MUTATORS AND ACCESSORS

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeOrderByName(Builder $query): Builder
    {
        $query->orderBy('title')->orderBy('body');
    }

    /**
     * @param $query
     * @param  array  $filters
     * @return void
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('body', 'like', '%' . $search . '%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    // SCOPES

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn() => route('posts.edit', $this->id),
        );
    }

    /**
     * Get the comments total
     * @return Attribute
     */
    protected function totalComments(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->comments->count(),
        );
    }
}
