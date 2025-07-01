<?php

namespace app\Http\Services\Api;

use Illuminate\Support\Facades\DB;
use app\Http\Services\Api\BaseApiService;
use app\Models\Category as CrudModel;

class CategoryService extends BaseApiService
{

    /**
     * The unnecessary fields for crud.
     * Example: if the data has translation fields, you can add them here. As a ('title', 'description')
     */
    protected $unnecessaryFieldsForCrud = [
        'image',
    ];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return CrudModel
     */
    public function createModel(array $data): CrudModel
    {
        $modelData              = $this->prepareModelData($data);
        $modelData['user_id']   = request()->user()->id;

        $model = DB::transaction(function () use($data, $modelData) {
            $model = CrudModel::create($modelData);

            $model = $this->uploadImageForModel($model, $data, 'images/categories');

            return $model;
        });

        return $model;
    }

    /**
     * Update the Model instance.
     */
    public function updateModel(CrudModel $model, array $data): CrudModel
    {
        $modelData              = $this->prepareModelData($data);
        $modelData['user_id']   = request()->user()->id;

        $model = DB::transaction(function () use($model, $data, $modelData) {
            $model->update($modelData);

            $model = $this->uploadImageForModel($model, $data, 'images/categories');

            return $model;
        });

        return $model;
    }
}
