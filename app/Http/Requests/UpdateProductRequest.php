<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('products', 'name')->ignore($product?->id)],
            'description' => ['nullable', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0.01'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'expiration_date' => ['nullable', 'date', 'after:today'],
        ];
    }
}
