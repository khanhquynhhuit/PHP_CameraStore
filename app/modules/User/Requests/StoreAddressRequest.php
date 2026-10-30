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
            // ĐÃ SỬA: Chặn ký tự đặc biệt, số. Yêu cầu ít nhất 2 ký tự.
            'receiver_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s]+$/u'],
            // ĐÃ SỬA: Ép buộc 10 số và bắt đầu bằng số 0
            'phone' => ['required', 'string', 'regex:/^0[0-9]{9}$/'],
            'province' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'detail' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Vui lòng nhập họ tên người nhận hàng.',
            'receiver_name.min' => 'Họ tên người nhận phải có ít nhất 2 ký tự.',
            'receiver_name.max' => 'Họ tên người nhận không được vượt quá 100 ký tự.',
            'receiver_name.regex' => 'Họ tên không hợp lệ, không được chứa số hoặc ký tự đặc biệt.',
            
            'phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 số và bắt đầu bằng số 0.',
            
            'province.required' => 'Vui lòng nhập Tỉnh / Thành phố.',
            'district.required' => 'Vui lòng nhập Quận / Huyện.',
            'ward.required' => 'Vui lòng nhập Phường / Xã.',
            'detail.required' => 'Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường).',
        ];
    }
}