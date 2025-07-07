<?php

namespace app\Http\Requests\Api;

use app\Http\Requests\Api\BaseApiRequest;

class SendPasswordResetRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email'  =>  ['required', 'exists:users,email'],
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
