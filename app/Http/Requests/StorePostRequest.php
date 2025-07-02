<?php

namespace App\Http\Requests;

use app\Http\Requests\Api\BaseApiRequest;

class StorePostRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title'             => ['required', 'string', 'min:2', 'max:255'],
            'body'              => ['required', 'string', 'min:2'],
            'published_at'      => ['nullable', 'date'],
            'category_ids'      => ['required', 'array', 'min:1'],
            'category_ids.*'    => ['required', 'exists:categories,id'],
        ];

        if ($this->isMethod('post') && $this->route()->getActionMethod() === 'store') {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:10240';
        } else {
            $rules['image'] = 'sometimes|image|mimes:jpeg,png,jpg,gif|max:10240';
        }

        return $rules;

    }
}
