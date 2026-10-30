<?php

namespace App\Modules\Order\Requests;

use App\Models\Address;
use App\Models\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // 1. Tự động lấy thông tin từ sổ địa chỉ nếu người dùng chọn địa chỉ đã lưu
        if ($this->filled('address_id') && $this->input('address_id') !== 'new') {
            $address = Address::where('user_id', $this->user()?->id)->find($this->input('address_id'));
            if ($address) {
                $fullAddress = trim("{$address->detail}, {$address->ward}".($address->district ? ", {$address->district}" : '').", {$address->province}", ', ');
                $this->merge([
                    'receiver_name' => $this->input('receiver_name') ?: $address->receiver_name,
                    'phone' => $this->input('phone') ?: $address->phone,
                    'shipping_address' => $this->input('shipping_address') ?: $fullAddress,
                ]);
            }
        }

        // 2. Chuẩn hóa số điện thoại người nhận: lọc số và đảm bảo tối đa 10 ký tự
        if ($this->filled('phone')) {
            $cleanPhone = preg_replace('/[^0-9]/', '', (string) $this->input('phone'));
            if (str_starts_with($cleanPhone, '84') && strlen($cleanPhone) === 11) {
                $cleanPhone = '0'.substr($cleanPhone, 2);
            }
            $cleanPhone = substr($cleanPhone, 0, 10);
            $this->merge(['phone' => $cleanPhone]);
        }
    }

    public function rules(): array
    {
        return [
            'address_id' => ['nullable'],
            // ĐÃ THÊM: min:2 và regex chỉ cho phép chữ cái & khoảng trắng (chặn #@!#, số)
            'receiver_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s]+$/u'],

            // ĐÃ THÊM: regex bắt buộc chuẩn 10 số và bắt đầu bằng số 0
            'phone' => ['required', 'string', 'regex:/^0[0-9]{9}$/'],

            // ĐÃ THÊM: min:10 để tránh người dùng nhập địa chỉ kiểu "đá" hay "a"
            'shipping_address' => ['required', 'string', 'min:10', 'max:500'],

            'payment_method' => ['required', new Enum(PaymentMethod::class)],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string', 'max:500'],
            'selected_ids' => ['nullable', 'array'],
            'selected_ids.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Họ tên người nhận không được để trống.',
            'receiver_name.min' => 'Họ tên người nhận phải có ít nhất 2 ký tự.',
            'receiver_name.max' => 'Họ tên người nhận không được vượt quá 100 ký tự.',
            'receiver_name.regex' => 'Họ tên người nhận không hợp lệ, không được chứa số hoặc ký tự đặc biệt.',

            'phone.required' => 'Số điện thoại người nhận không đúng hoặc đang để trống.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng 10 số và bắt đầu bằng số 0.',

            'shipping_address.required' => 'Địa chỉ giao hàng không được để trống.',
            'shipping_address.min' => 'Địa chỉ giao hàng quá ngắn, vui lòng nhập chi tiết hơn.',
            'shipping_address.max' => 'Địa chỉ giao hàng không được vượt quá 500 ký tự.',

            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.Illuminate\Validation\Rules\Enum' => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}
