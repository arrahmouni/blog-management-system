<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use app\Http\Services\Api\ProductService;
use App\Http\Requests\StoreProductRequest;
use app\Http\Controllers\Api\BaseApiController;

class ProductController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = ProductResource::class;

    protected $modelRequest = StoreProductRequest::class;

    public function __construct(Product $model, ProductService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }
}
