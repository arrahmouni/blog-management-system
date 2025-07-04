<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Spatie\Image\Enums\CropPosition;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'slug',
        'is_published',
        'published_at',
    ];

    protected $appends = ['published_at_format', 'created_at_format', 'image_url'];

    protected $with = ['categories', 'user'];

    public const MEDIA_COLLECTION = 'posts';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['title', 'body', 'is_published', 'published_at'])
        ->logOnlyDirty();
    }

    // Perform crop on image via media library
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb-100')
            ->crop(100, 100, CropPosition::Center)
            ->performOnCollections($this::MEDIA_COLLECTION);
    }

    public function getDataForApi($data, $isCollection = false) : mixed
    {
        $modelCollection = $this->query();
        $user            = request()->user();

        if($user) {
            if($user->isAdmin()) {
                $modelCollection->withTrashed();
            } elseif($user->isWriter()) {
                $modelCollection->where('user_id', $user->id);
            } elseif($user->isUser()) {
                $modelCollection->published();
            }

            if($isCollection) {
                return $modelCollection->orderBy('id', 'desc');
            }
            return $modelCollection->findOrFail($data['id']);
        }

        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected function createdAtFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['created_at'])->diffForHumans(),
        );
    }

    protected function publishedAtFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($attributes['published_at']) ? Carbon::parse($attributes['published_at'])->diffForHumans() : null,
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl(self::MEDIA_COLLECTION),
        );
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
