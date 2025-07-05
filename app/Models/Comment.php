<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\NewCommentNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
        'is_accepted',
    ];

    protected $appends = ['created_at_format'];

    protected static function booted()
    {
        static::created(function ($comment) {
            if($comment->is_accepted) {
                $comment->post->user->notify(
                    new NewCommentNotification($comment, $comment->post)
                );
            }
        });

        static::updated(function ($comment) {
            if (
                $comment->wasChanged('is_accepted') &&
                (bool) $comment->getOriginal('is_accepted') !== (bool) $comment->is_accepted &&
                $comment->is_accepted
            ) {
                $comment->post->user->notify(
                    new NewCommentNotification($comment, $comment->post)
                );
            }
        });

    }


    public function getDataForApi($isCollection = false) : mixed
    {
        $modelCollection = $this->with('post', 'user');
        $user = request()->user();

        if($user) {
            if(! $user->isAdmin()) {
                $modelCollection->accepted();
            }

            if($isCollection) {
                return $modelCollection->where('post_id', request()->route('post'))->orderBy('id', 'desc');
            }

            return $modelCollection->findOrFail(request()->route('comment'));
        }

        return null;
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAccepted($query)
    {
        return $query->where('is_accepted', true);
    }

    protected function createdAtFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['created_at'])->diffForHumans(),
        );
    }
}
