<?php

namespace App\Models;

use App\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Image\Enums\CropPosition;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, HasSlug, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'slug',
        'is_published',
        'published_at',
    ];

    public const MEDIA_COLLECTION = 'posts';

    // Perform crop on image via media library
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb-100')
            ->crop(100, 100, CropPosition::Center)
            ->performOnCollections($this::MEDIA_COLLECTION);
    }

    public function writer(): BelongsTo
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
}
