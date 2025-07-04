<?php

namespace app\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use app\Http\Resources\PaginateResource;

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
     * Merge data to request for collection
     *
     *  @param Request $request
     *  @return void
     */
    public function mergeDataToRequestForCollection(Request $request)
    {
        $request->merge([]);
    }

    /**
     *  Merge data to request
     *
     *  @param Request $request
     *  @return void
     */
    public function mergeDataToRequest(Request $request)
    {
        $request->merge([]);
    }

    /**
     *  Display a listing of the resource.
     *
     *  @param Request $request
     *  @return void
     */
    public function index(Request $request)
    {
        $this->mergeDataToRequestForCollection($request);

        $this->data['model']    = $this->model->getDataForApi($request->all(), isCollection: true);
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
        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

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
        app($this->modelRequest);

        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

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
        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

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
        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

        $this->data['model']->restore();

        return sendApiSuccessResponse('Restored successfully');
    }


    public function forceDelete(Request $request)
    {
        $this->mergeDataToRequest($request);

        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

        $result = $this->canDelete($this->data['model']);

        if(! $result['success']) {
            return sendApiFailResponse($result['message']);
        }

        $this->data['model']->forceDelete();

        return sendApiSuccessResponse('Deleted successfully');
    }
}
