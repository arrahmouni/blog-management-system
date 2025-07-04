<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $properties = $this->properties ?? [];
        $changes = [];

        if (isset($properties['attributes']) || isset($properties['old'])) {
            $attributes = $properties['attributes'] ?? [];
            $oldValues = $properties['old'] ?? [];

            $allKeys = array_unique(array_merge(array_keys($attributes), array_keys($oldValues)));

            foreach ($allKeys as $key) {
                $oldValue = $oldValues[$key] ?? null;
                $newValue = $attributes[$key] ?? null;

                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'old' => $this->formatValue($oldValue),
                        'new' => $this->formatValue($newValue),
                    ];
                }
            }
        }

        return [
            'id'            => $this->id,
            'log_name'      => $this->log_name,
            'description'   => $this->description,
            'event'         => $this->event,
            'subject_id'    => $this->subject_id,
            'subject_type'  => $this->subject_type,
            'causer'        => $this->causer ? [
                'id'        => $this->causer->id,
                'name'      => $this->causer->name ?? 'System',
                'email'     => $this->causer->email ?? null,
            ] : null,
            'properties'    => $properties,
            'changes'       => $changes,
            'created_at'    => $this->created_at->toDateTimeString(),
            'updated_at'    => $this->updated_at->toDateTimeString(),
        ];
    }

    protected function formatValue($value)
    {
        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }

        if (is_null($value)) {
            return 'N/A';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }
}
