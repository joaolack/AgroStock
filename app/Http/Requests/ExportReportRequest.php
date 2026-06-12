<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $reportTypeRule = $this->routeIs('export.index') ? 'nullable' : 'required';

        return [
            'report_type' => [$reportTypeRule, 'in:general_stock,critical_stock,financial,by_supplier,most_profitable'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'stock_status' => ['nullable', 'in:all,in_stock,low_stock,out_of_stock'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function filters(): array
    {
        $filters = $this->validated();

        if (
            isset($filters['price_min'], $filters['price_max']) &&
            (float) $filters['price_min'] > (float) $filters['price_max']
        ) {
            $filters['price_max'] = $filters['price_min'];
        }

        return $filters;
    }
}
