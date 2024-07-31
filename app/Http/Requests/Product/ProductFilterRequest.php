<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'attributes' => 'array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ];
    }
}
