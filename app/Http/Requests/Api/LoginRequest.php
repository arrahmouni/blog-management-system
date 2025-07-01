<?php

namespace app\Http\Requests\Api;

use app\Http\Requests\Api\BaseApiRequest;

class LoginRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'login'         => ['required', 'string'],
            'password'      => ['required', 'string'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
