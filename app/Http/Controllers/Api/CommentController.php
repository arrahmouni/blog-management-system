<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use App\Http\Services\Api\CommentService;
use App\Http\Requests\StoreCommentRequest;
use app\Http\Controllers\Api\BaseApiController;

class CommentController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = CommentResource::class;

    protected $modelRequest = StoreCommentRequest::class;

    public function __construct(Comment $model, CommentService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

    public function mergeDataToRequestForCollection(Request $request)
    {
        $request->merge([
            'post_id'   => $request->post
        ]);
    }

    public function mergeDataToRequest(Request $request)
    {
        $request->merge([
            'post_id'   => $request->post,
            'id'        => $request->comment
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        app($this->modelRequest);

        $post = Post::published()->find(request()->post);

        if (! $post) {
            return sendApiFailResponse('Post not found');
        }

        $result = $this->modelService->createModel(app($this->modelRequest)->validated(), $post);

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Created successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }

    public function accept(Request $request)
    {
        $result = $this->modelService->accept($request->comment);

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Accepted successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }

    public function reject(Request $request)
    {
        $result = $this->modelService->reject($request->comment);

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Rejected successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }
}
