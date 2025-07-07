<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Services\Api\PostService;
use App\Http\Requests\StorePostRequest;
use app\Http\Controllers\Api\BaseApiController;
use Illuminate\Support\Facades\Gate;

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

    public function approve(Post $post)
    {
        Gate::authorize('approve', $post);

        $result = $this->modelService->approve($post);

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Approved successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }
}
