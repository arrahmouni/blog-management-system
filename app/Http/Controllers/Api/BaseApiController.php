<?php

namespace app\Http\Controllers\Api;

use Illuminate\Support\Str;
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
        $this->data['model_plural']   = Str::plural(Str::snake(class_basename($this->model)));
    }

    /**
     * Send a listing of the resource to ajax.
     */
    public function index(Request $request)
    {
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
     */
    public function show(Request $request)
    {
        $this->data['model'] = $this->model->getDataForApi($request->all(), isCollection: false);

        return sendApiSuccessResponse(data: [
            'data' => new $this->modelResource($this->data['model']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
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
     */
    public function update(Request $request)
    {
        app($this->modelRequest);

        $this->data['model'] = $this->model->getDataForApi($request->id, isCollection: false);

        $this->modelService->updateModel($this->data['model'], app($this->modelRequest)->validated());

        return sendApiSuccessResponse('Updated successfully', [
            'data' => new $this->modelResource($this->data['model']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->data['model'] = $this->model->getDataForApi($request->id, isCollection: false);

        $this->data['model']->delete();

        return sendApiSuccessResponse('Deleted successfully');
    }
}
