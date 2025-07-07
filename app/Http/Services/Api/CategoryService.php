<?php

namespace app\Http\Services\Api;

use Illuminate\Support\Facades\DB;
use app\Http\Services\Api\BaseApiService;
use app\Models\Category as CrudModel;

class CategoryService extends BaseApiService
{
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

            return $model;
        });

        return $model;
    }
}
