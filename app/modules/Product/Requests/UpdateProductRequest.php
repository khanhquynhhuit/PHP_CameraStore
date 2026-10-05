<?php

namespace App\Modules\Product\Requests;

use App\Models\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ?? $this->route('id') ?? $this->input('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'brand_id' => ['sometimes', 'required', 'exists:brands,id'],
            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'warranty_months' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', new Enum(ProductStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu đã chọn không hợp lệ.',
            'sku.required' => 'Mã SKU không được để trống.',
            'sku.unique' => 'Mã SKU này đã tồn tại trong hệ thống.',
            'price.required' => 'Giá bán không được để trống.',
            'price.numeric' => 'Giá bán phải là chữ số.',
            'stock.required' => 'Số lượng tồn kho không được để trống.',
        ];
    }
}
