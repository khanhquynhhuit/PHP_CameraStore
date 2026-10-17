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
            'name' => ['sometimes', 'required', 'string', 'max:200'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'brand_id' => ['sometimes', 'required', 'exists:brands,id'],
            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:30',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'gte:price'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'warranty_months' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', new Enum(ProductStatus::class)],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm máy ảnh không được để trống.',
            'category_id.required' => 'Vui lòng chọn danh mục cho sản phẩm.',
            'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu đã chọn không hợp lệ.',
            'sku.required' => 'Mã SKU không được để trống.',
            'sku.max' => 'Mã SKU không được vượt quá 30 ký tự.',
            'sku.unique' => 'Mã SKU này đã tồn tại trong hệ thống.',
            'price.required' => 'Giá bán không được để trống.',
            'price.numeric' => 'Giá bán phải là định dạng số.',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'original_price.numeric' => 'Giá gốc niêm yết phải là định dạng số.',
            'original_price.gte' => 'Giá gốc niêm yết phải lớn hơn hoặc bằng giá bán.',
            'stock.required' => 'Số lượng tồn kho không được để trống.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'image.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'image.max' => 'Dung lượng hình ảnh không được vượt quá 2MB.',
        ];
    }
}
