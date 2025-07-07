<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use App\Http\Services\Api\CommentService;
use App\Http\Requests\StoreCommentRequest;
use app\Http\Controllers\Api\BaseApiController;
use Illuminate\Support\Facades\Gate;

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

        $message = $result->is_accepted ? 'Comment Created successfully' : 'Comment created successfully, waiting for approval';

        return sendApiSuccessResponse($message, data: [
            'data' => new $this->modelResource($result),
        ]);
    }

    public function approve(Comment $comment)
    {
        Gate::authorize('approve', $comment);

        $result = $this->modelService->approve($comment);

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Accepted successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }
}
