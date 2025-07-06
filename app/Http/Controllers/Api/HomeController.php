<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use app\Http\Resources\PaginateResource;
use app\Http\Controllers\Api\BaseApiController;

class HomeController extends BaseApiController
{
    public function posts(Request $request)
    {
        $query = Post::published();

        if($request->featured) {
            $query = $query->HasMostComments();
        }

        if($request->limit) {
            $this->data['data'] = $query->limit($request->limit)->get();

            return sendApiSuccessResponse(data: [
                'data' => PostResource::collection($this->data['data']),
            ]);
        }

        $page                   = $request->has('page') ? $request->page : 1;
        $perPage                = config('app.pagination');
        $this->data['data']     = $query->paginate($perPage, ['*'], 'page', $page);
        $this->data['paginate'] = new PaginateResource($this->data['data']);

        return sendApiSuccessResponse(data: [
            'data'      => PostResource::collection($this->data['data']),
            'paginate'  => $this->data['paginate'],
        ]);

        return sendApiSuccessResponse(data: $this->data);
    }

    public function PostDetails(Request $request)
    {
        $post = Post::published()->where('slug', $request->slug)->firstOrFail();

        return sendApiSuccessResponse(data: [
            'data' => new PostResource($post),
        ]);
    }

    public function postComments(Request $request)
    {
        $post     = Post::published()->with('comments')->findOrFail($request->post);
        $query    = $post->comments()->with('user')->accepted();

        $page                   = $request->has('page') ? $request->page : 1;
        $perPage                = config('app.pagination');
        $this->data['data']     = $query->latest()->paginate($perPage, ['*'], 'page', $page);
        $this->data['paginate'] = new PaginateResource($this->data['data']);

        return sendApiSuccessResponse(data: [
            'data'      => CommentResource::collection($this->data['data']),
            'paginate'  => $this->data['paginate'],
        ]);

    }
}
