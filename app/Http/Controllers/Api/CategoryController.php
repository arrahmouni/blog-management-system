<?php

namespace App\Http\Controllers\Api;

use app\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use app\Http\Services\Api\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = CategoryResource::class;

    protected $modelRequest = StoreCategoryRequest::class;

    public function __construct(Category $model, CategoryService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

    public function mergeDataToRequest(Request $request)
    {
        $request->merge([
            'id' => $request->category
        ]);
    }

    public function canDelete($model)
    {
        if($model->posts()->exists()) return sendFailInternalResponse('Category has posts, cannot delete');

        return sendSuccessInternalResponse();
    }
}
