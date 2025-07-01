<?php

namespace app\Http\Services\Api;

use Illuminate\Support\Facades\DB;
use app\Http\Services\Api\BaseApiService;
use app\Models\Product as CrudModel;

class ProductService extends BaseApiService
{
    /**
     * The unnecessary fields for crud.
     * Example: if the data has translation fields, you can add them here. As a ('title', 'description')
     */
    protected $unnecessaryFieldsForCrud = [
        'image',
        'category_ids',
    ];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return CrudModel
     */
    public function createModel(array $data): CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $model = DB::transaction(function () use($data, $modelData) {
            $model = CrudModel::create($modelData);

            $model->categories()->sync($data['category_ids']);

            $model = $this->uploadImageForModel($model, $data, 'images/products');

            return $model;
        });

        return $model;
    }

    /**
     * Update the Model instance.
     */
    public function updateModel(CrudModel $model, array $data): CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $model = DB::transaction(function () use($model, $data, $modelData) {
            $model->update($modelData);

            $model->categories()->sync($data['category_ids']);

            $model = $this->uploadImageForModel($model, $data, 'images/products');

            return $model;
        });

        return $model;
    }

}
