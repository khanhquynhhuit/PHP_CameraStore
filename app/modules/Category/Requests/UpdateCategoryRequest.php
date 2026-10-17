<?php

namespace App\Modules\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') ?? $this->route('id') ?? $this->input('id');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.string' => 'Tên danh mục phải là chuỗi ký tự hợp lệ.',
            'name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'name.unique' => 'Tên danh mục này đã tồn tại trong hệ thống.',
            'slug.max' => 'Đường dẫn tĩnh (Slug) không được vượt quá 120 ký tự.',
            'slug.unique' => 'Đường dẫn tĩnh (Slug) này đã tồn tại trong hệ thống.',
            'is_active.boolean' => 'Trạng thái hoạt động không hợp lệ.',
        ];
    }
}
