<?php

namespace App\Http\Services\Api;

use Illuminate\Support\Facades\DB;
use app\Http\Services\Api\BaseApiService;
use app\Models\Post as CrudModel;

class PostService extends BaseApiService
{

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
        $modelData                  = $this->prepareModelData($data);
        $user                       = request()->user();
        $modelData['user_id']       = $user->id;
        $modelData['is_published']  = $user->isAdmin() ? true : false; // only admin can publish without approval
        $modelData['published_at']  = $user->isAdmin() ? now() : null; // only admin can publish without approval
        
        $model = DB::transaction(function () use($data, $modelData) {
            $model = CrudModel::create($modelData);

            $this->syncCategoryIds($model, $data['category_ids']);

            $this->uploadImageForModel($model, $data, CrudModel::MEDIA_COLLECTION);

            return $model->refresh();
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

            $this->syncCategoryIds($model, $data['category_ids']);

            $this->uploadImageForModel($model, $data, CrudModel::MEDIA_COLLECTION);

            return $model->refresh();
        });

        return $model;
    }

    private function syncCategoryIds(CrudModel $model, array $categoryIds) {
        $model->categories()->sync($categoryIds);
    }

    public function approve($postId)
    {
        $model = CrudModel::find($postId);

        if(!$model) return sendFailInternalResponse('Post not found');

        $model->is_published = true;
        $model->published_at  = now();
        $model->save();

        return $model->refresh();
    }

    public function reject($postId)
    {
        $model = CrudModel::find($postId);

        if(!$model) return sendFailInternalResponse('Post not found');

        $model->is_published = false;
        $model->published_at  = null;
        $model->save();

        return $model->refresh();
    }
}
