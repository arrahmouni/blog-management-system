<?php

use App\Enums\HttpStatusCode;
use Illuminate\Validation\Validator;

if(!function_exists('sendApiSuccessResponse'))
{
    function sendApiSuccessResponse(string $message = 'Data fetched successfully', array $data = [], int $code = HttpStatusCode::OK->value)
    {
        return response()->json([
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => (object) $data,
            'errors'  => (object) [],
        ], $code);
    }
}

if(!function_exists('sendApiFailResponse'))
{
    function sendApiFailResponse(string $message = 'Something went wrong', array $errors = [], int $code = HttpStatusCode::BAD_REQUEST->value)
    {
        return response()->json([
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) $errors,
        ], $code);
    }
}

if(!function_exists('sendSuccessInternalResponse'))
{
    function sendSuccessInternalResponse(string|null $message = null, array $data = [], int $code = HttpStatusCode::OK->value)
    {
        return [
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
            'errors'  => (object) [],
        ];
    }
}

if(!function_exists('sendFailInternalResponse'))
{
    function sendFailInternalResponse(string|null $message = null, array $errors = [], int $code = HttpStatusCode::BAD_REQUEST->value)
    {
        return [
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) $errors,
        ];
    }
}

if(!function_exists('sendValidationResponse'))
{
    function sendValidationResponse(Validator $validator, string $message = 'Validation failed')
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::UNPROCESSABLE_ENTITY->value,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => $validator->errors(),
        ], HttpStatusCode::UNPROCESSABLE_ENTITY->value);
    }
}

if(!function_exists('sendUnauthorizedResponse'))
{
    function sendUnauthorizedResponse(string $message = 'Unauthorized')
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::UNAUTHORIZED->value,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) [],
        ], HttpStatusCode::UNAUTHORIZED->value);
    }
}

if(!function_exists('sendDontHavePermissionResponse'))
{
    function sendDontHavePermissionResponse(string $message = 'You don\'t have permission to perform this action')
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::FORBIDDEN->value,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) [],
        ], HttpStatusCode::FORBIDDEN->value);
    }
}

if(!function_exists('sendNotFoundResponse'))
{
    function sendNotFoundResponse(string $message = 'Resource not found')
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::NOT_FOUND->value,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) [],
        ], HttpStatusCode::NOT_FOUND->value);
    }
}

if(!function_exists('sendMethodNotAllowedResponse'))
{
    function sendMethodNotAllowedResponse(string $message = 'Method not allowed')
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::METHOD_NOT_ALLOWED->value,
            'message' => $message,
            'data'    => (object) [],
            'errors'  => (object) [],
        ], HttpStatusCode::METHOD_NOT_ALLOWED->value);
    }
}

if(!function_exists('sendServerErrorResponse'))
{
    function sendServerErrorResponse()
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::INTERNAL_SERVER_ERROR->value,
            'message' => 'Something went wrong',
            'data'    => (object) [],
            'errors'  => (object) [],
        ], HttpStatusCode::INTERNAL_SERVER_ERROR->value);
    }
}
