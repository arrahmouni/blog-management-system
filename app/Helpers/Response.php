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
    function sendValidationResponse(Validator $validator)
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::UNPROCESSABLE_ENTITY->value,
            'message' => 'Validation failed',
            'data'    => (object) [],
            'errors'  => $validator->errors(),
        ], HttpStatusCode::UNPROCESSABLE_ENTITY->value);
    }
}

if(!function_exists('sendUnauthorizedResponse'))
{
    function sendUnauthorizedResponse()
    {
        return response()->json([
            'success' => false,
            'code'    => HttpStatusCode::UNAUTHORIZED->value,
            'message' => 'Unauthorized',
            'data'    => (object) [],
            'errors'  => (object) [],
        ], HttpStatusCode::UNAUTHORIZED->value);
    }
}
