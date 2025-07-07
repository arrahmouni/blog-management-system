<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\UpgradeRequestStatuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UpgradeRequests extends Model
{
    protected $fillable = [
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = [
        'created_at_format',
    ];

    public function getDataForApi($isCollection = false) : mixed
    {
        $modelCollection = $this->with('user');

        if($isCollection) {
            return $modelCollection->orderBy('id', 'desc');
        }

        return $modelCollection->findOrFail(request()->route('request'));
    }

    public function scopePending($query)
    {
        return $query->where('status', UpgradeRequestStatuses::PENDING->value);
    }

    protected function createdAtFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['created_at'])->diffForHumans(),
        );
    }
}
