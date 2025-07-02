<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
        'is_accepted',
    ];

    protected $appends = ['created_at_format'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['body', 'is_accepted'])
        ->logOnlyDirty();
    }

    public function getDataForApi($data, $isCollection = false) : mixed
    {
        $modelCollection = $this->with('post', 'user');
        $user = request()->user();

        if($user) {
            if(! $user->isAdmin()) {
                $modelCollection->accepted();
            }

            if($isCollection) {
                return $modelCollection->latest();
            }

            return $modelCollection->findOrFail($data['id']);
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
