<?php

namespace App\Modules\User\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            // ĐÃ SỬA: Ràng buộc bắt buộc 10 chữ số, bắt đầu bằng số 0
            'phone' => ['nullable', 'string', 'regex:/^0[0-9]{9}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Địa chỉ email không được để trống.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng bởi tài khoản khác.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 số và bắt đầu bằng số 0.',
        ];
    }
}
