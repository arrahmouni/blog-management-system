<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Generate and set a unique slug based on a given field.
     */
    public static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->generateSlugOnCreate();
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->slugSourceField ?? 'title')) {
                $model->generateSlugOnUpdate();
            }
        });
    }

    protected function generateSlugOnCreate()
    {
        $sourceField = $this->slugSourceField ?? 'title';
        $slug = Str::slug($this->$sourceField);
        $this->slug = $this->getUniqueSlug($slug);
    }

    protected function generateSlugOnUpdate()
    {
        $sourceField = $this->slugSourceField ?? 'title';
        $slug = Str::slug($this->$sourceField);
        $this->slug = $this->getUniqueSlug($slug, $this->id);
    }

    protected function getUniqueSlug($baseSlug, $ignoreId = null)
    {
        $slug = $baseSlug;
        $counter = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
