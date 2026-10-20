<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:15'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'detail' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Vui lòng nhập họ tên người nhận hàng.',
            'receiver_name.max' => 'Họ tên người nhận không được vượt quá 100 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'province.required' => 'Vui lòng nhập Tỉnh / Thành phố.',
            'district.required' => 'Vui lòng nhập Quận / Huyện.',
            'ward.required' => 'Vui lòng nhập Phường / Xã.',
            'detail.required' => 'Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường).',
        ];
    }
}
