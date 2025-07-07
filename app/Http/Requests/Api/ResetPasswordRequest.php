<?php

namespace app\Http\Requests\Api;

use app\Http\Requests\Api\BaseApiRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email'     =>  ['required', 'email', 'exists:users,email'],
            'token'     =>  ['required'],
            'password'  =>  ['required', 'confirmed', Password::defaults()],
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
