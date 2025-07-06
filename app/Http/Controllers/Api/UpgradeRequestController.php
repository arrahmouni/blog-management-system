<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UpgradeRequests;
use Illuminate\Support\Facades\Gate;
use App\Enums\UpgradeRequestStatuses;
use App\Http\Resources\UpgradeRequestResource;
use app\Http\Controllers\Api\BaseApiController;
use App\Http\Services\Api\UpgradeRequestService;

class UpgradeRequestController extends BaseApiController
{
    protected $model;

    protected $modelService;

    protected $modelResource = UpgradeRequestResource::class;

    protected $modelRequest  = Request::class;

    public function __construct(UpgradeRequests $model, UpgradeRequestService $modelService)
    {
        $this->model        = $model;
        $this->modelService = $modelService;

        parent::__construct();
    }

    public function apply(Request $request)
    {
        $user = request()->user();

        if($user->upgradeRequests()->pending()->exists()) return sendApiSuccessResponse('You already have a pending request');

        try {
            $this->modelService->createRequest($user);

            return sendApiSuccessResponse('Request sent successfully');
        } catch (\Exception $e) {
            return sendServerErrorResponse();
        }
    }

    public function getStatus()
    {
        return sendApiSuccessResponse(data: [
            'has_pending' => request()->user()->upgradeRequests()->pending()->exists(),
        ]);
    }

    public function accept(UpgradeRequests $upgradeRequests)
    {
        Gate::authorize('accept', $upgradeRequests);

        if($upgradeRequests->status != UpgradeRequestStatuses::PENDING->value) return sendApiSuccessResponse('Request already approved');

        try {
            $this->modelService->accept($upgradeRequests);

            return sendApiSuccessResponse('Request approved successfully');
        } catch (\Exception $e) {
            return sendServerErrorResponse();
        }
    }

    public function reject(UpgradeRequests $upgradeRequests)
    {
        Gate::authorize('reject', $upgradeRequests);

        if($upgradeRequests->status != UpgradeRequestStatuses::PENDING->value) return sendApiSuccessResponse('Request already rejected');

        try {
            $this->modelService->reject($upgradeRequests);

            return sendApiSuccessResponse('Request rejected successfully');
        } catch (\Exception $e) {
            return sendServerErrorResponse();
        }
    }
}
