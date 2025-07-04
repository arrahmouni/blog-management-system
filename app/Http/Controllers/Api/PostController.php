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

    public function approve(Request $request)
    {
        $result = $this->modelService->approve($request->post);

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Approved successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }

    public function reject(Request $request)
    {
        $result = $this->modelService->reject($request->post);

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Rejected successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }
}
