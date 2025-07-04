<?php

namespace app\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use app\Http\Resources\PaginateResource;
use Illuminate\Support\Facades\Gate;
class BaseApiController extends Controller
{
    public $data = [];

    protected $model;

    protected $modelResource;

    protected $modelService;

    protected $modelRequest;

    public $supportedLang;

    public $defaultLocale;

    public $locale;

    public function __construct()
    {
    }

    /**
     *  Display a listing of the resource.
     *
     *  @param Request $request
     *  @return void
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', $this->model);

        $this->data['model']    = $this->model->getDataForApi(true);
        $this->data['page']     = $request->has('page') ? $request->page : 1;

        $perPage = config('app.pagination');

        $this->data['data']     = $this->data['model']->paginate($perPage, ['*'], 'page', $this->data['page']);
        $this->data['paginate'] = new PaginateResource($this->data['data']);

        return sendApiSuccessResponse(data: [
            'data'      => $this->modelResource::collection($this->data['data']),
            'paginate'  => $this->data['paginate'],
        ]);
    }

    /**
     * Display the specified resource to api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->data['model'] = $this->model->getDataForApi();

        Gate::authorize('view', $this->data['model']);

        return sendApiSuccessResponse(data: [
            'data' => new $this->modelResource($this->data['model']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Gate::authorize('create', $this->model);

        app($this->modelRequest);

        $result = $this->modelService->createModel(app($this->modelRequest)->validated());

        if(is_array($result) && isset($result['success']) && ! $result['success']) {
            return sendApiFailResponse($result['message'], $result['errors']);
        }

        return sendApiSuccessResponse('Created successfully', data: [
            'data' => new $this->modelResource($result),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->data['model'] = $this->model->getDataForApi();

        Gate::authorize('update', $this->data['model']);

        app($this->modelRequest);

        $this->modelService->updateModel($this->data['model'], app($this->modelRequest)->validated());

        return sendApiSuccessResponse('Updated successfully', [
            'data' => new $this->modelResource($this->data['model']),
        ]);
    }

    /**
     * Check if the model can be deleted or not.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    public function canDelete($model)
    {
        return sendSuccessInternalResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->data['model'] = $this->model->getDataForApi();

        Gate::authorize('delete', $this->data['model']);

        $result = $this->canDelete($this->data['model']);

        if(! $result['success']) {
            return sendApiFailResponse($result['message']);
        }

        $this->data['model']->delete();

        return sendApiSuccessResponse('Deleted successfully');
    }

    /**
     * Restore the specified resource from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $this->data['model'] = $this->model->getDataForApi();

        Gate::authorize('restore', $this->data['model']);

        $this->data['model']->restore();

        return sendApiSuccessResponse('Restored successfully');
    }


    public function forceDelete(Request $request)
    {
        $this->data['model'] = $this->model->getDataForApi();

        Gate::authorize('forceDelete', $this->data['model']);

        $result = $this->canDelete($this->data['model']);

        if(! $result['success']) {
            return sendApiFailResponse($result['message']);
        }

        $this->data['model']->forceDelete();

        return sendApiSuccessResponse('Deleted successfully');
    }
}
