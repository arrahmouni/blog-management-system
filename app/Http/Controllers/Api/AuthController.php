<?php

namespace app\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Enums\HttpStatusCode;
use app\Http\Services\Api\AuthService;
use app\Http\Requests\Api\LoginRequest;
use app\Http\Requests\Api\RegisterRequest;
use app\Http\Controllers\Api\BaseApiController;
use app\Http\Requests\Api\ResetPasswordRequest;
use app\Http\Requests\Api\SendPasswordResetRequest;
use App\Http\Resources\UserResource;

class AuthController extends BaseApiController
{

    public function __construct(protected AuthService $authService)
    {
        parent::__construct();
    }

    public function register(RegisterRequest $request)
    {
        $response = $this->authService->register($request->validated());

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->validated());

        if(! $response['success']) {
            return sendApiFailResponse($response['message'], code: $response['code'] ?? HttpStatusCode::BAD_REQUEST->value);
        }

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function sendResetPasswordLink(SendPasswordResetRequest $request)
    {
        $response = $this->authService->sendResetPasswordLink($request->validated());

        if(! $response['success']) {
            return sendApiFailResponse($response['message']);
        }

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $response = $this->authService->resetPassword($request->validated());

        if(! $response['success']) {
            return sendApiFailResponse($response['message'], code: $response['code'] ?? HttpStatusCode::BAD_REQUEST->value);
        }

        return sendApiSuccessResponse($response['message'], $response['data']);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $response = $this->authService->logout($user);

        return sendApiSuccessResponse($response['message'], $response['data']);
    }
}
