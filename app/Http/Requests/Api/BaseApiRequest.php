<?php

namespace app\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BaseApiRequest extends FormRequest
{
    protected function isUpdate(): bool
    {
        return in_array($this->method(), ['PUT', 'PATCH']);
    }

    protected function isCreate(): bool
    {
        return in_array($this->method(), ['POST']);
    }

    protected function getAuthUser(): mixed
    {
        return request()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

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
