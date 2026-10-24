<?php

namespace App\Modules\Review\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'integer',
                'exists:orders,id',
            ],
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Vui lòng chọn đơn hàng đã mua sản phẩm.',

            'order_id.exists' => 'Đơn hàng được chọn không tồn tại.',

            'rating.required' => 'Vui lòng chọn số sao đánh giá.',

            'rating.integer' => 'Số sao đánh giá không hợp lệ.',

            'rating.between' => 'Số sao phải nằm trong khoảng từ 1 đến 5.',

            'comment.max' => 'Nội dung đánh giá không được vượt quá 1.000 ký tự.',
        ];
    }
}
