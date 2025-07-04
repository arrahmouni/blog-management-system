<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasSlug;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasSlug, LogsActivity;

    protected $fillable = ['title', 'slug'];

    protected $appends = [
        'created_at_format',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['title'])
        ->logOnlyDirty();
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function getDataForApi($isCollection = false) : mixed
    {
        $modelCollection = $this->query();

        if(request()->user()->isAdmin()) $modelCollection->withTrashed();

        if($isCollection) {
            return $modelCollection->orderBy('id', 'desc');
        }

        return $modelCollection->findOrFail(request()->route('category'));
    }

    protected function createdAtFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['created_at'])->diffForHumans(),
        );
    }
}
