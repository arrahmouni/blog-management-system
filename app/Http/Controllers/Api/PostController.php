<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Services\Api\PostService;
use App\Http\Requests\StorePostRequest;
use app\Http\Controllers\Api\BaseApiController;

class PostController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = PostResource::class;

    protected $modelRequest = StorePostRequest::class;

    public function __construct(Post $model, PostService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

    public function mergeDataToRequest(Request $request)
    {
        $request->merge([
            'id' => $request->post
        ]);
    }
}
