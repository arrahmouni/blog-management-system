<?php

namespace App\Http\Services\Api;

use Illuminate\Support\Facades\DB;
use app\Http\Services\Api\BaseApiService;
use app\Models\Comment as CrudModel;
use App\Models\Post;

class CommentService extends BaseApiService
{
    protected $unnecessaryFieldsForCrud = [];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return mixed
     */
    public function createModel(array $data, $post): mixed
    {
        $user                     = request()->user();
        $modelData                = $this->prepareModelData($data);
        $modelData['user_id']     = $user->id;
        $modelData['is_accepted'] = $user->isAdmin() ? true : false;

        $model = DB::transaction(function () use($data, $modelData, $post) {
            $model = $post->comments()->create($modelData);

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

            return $model->refresh();
        });

        return $model;
    }

    public function approve($model)
    {
        $model->is_accepted = true;
        $model->save();

        return $model->refresh();
    }
}
