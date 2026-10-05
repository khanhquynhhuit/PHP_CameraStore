<?php

namespace App\Modules\Cart\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Vui lòng chọn sản phẩm cần thêm.',
            'product_id.exists' => 'Sản phẩm đã chọn không tồn tại trong hệ thống.',
            'quantity.required' => 'Số lượng sản phẩm không được để trống.',
            'quantity.integer' => 'Số lượng sản phẩm phải là số nguyên.',
            'quantity.min' => 'Số lượng tối thiểu là 1.',
        ];
    }
}
