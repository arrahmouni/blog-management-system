<?php

namespace app\Http\Services\Api;

use Illuminate\Support\Facades\Storage;

class BaseApiService
{
    public $data = [];

    /**
     * Fields that are not necessary for creating or updating the model.
     */
    protected $unnecessaryFieldsForCrud = [];

    public function __construct()
    {

    }

    /**
     * Remove unnecessary fields from the data array before creating or updating the model.
     *
     * @param array $data
     * @return array
     */
    protected function prepareModelData(array $data) : array
    {
        $data = array_diff_key($data, array_flip($this->unnecessaryFieldsForCrud));

        return $data;
    }

    /**
     * Upload image for the model.
     *
     * @param mixed $model
     * @param array $data
     * @param string $collection
     * @param string $field
     * @return mixed
     */
    protected function uploadImageForModel(mixed $model, array $data, string $collection, string $field = 'image'): mixed
    {
        if(isset($data[$field])) {
            $media = $model->getFirstMedia($collection);

            if($media) {
                $media->delete();
            }

            $model->addMedia($data[$field])
                ->toMediaCollection($collection);
        }

        return $model;
    }
}


