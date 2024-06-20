<?php

namespace Module\Product\Controllers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price_in_cents' => ['required', 'integer', 'min:0'],
            'photo' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
