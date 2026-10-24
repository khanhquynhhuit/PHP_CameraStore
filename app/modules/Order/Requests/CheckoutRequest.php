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
            'receiver_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:10'],
            'shipping_address' => ['required', 'string', 'max:500'],
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
            'receiver_name.max' => 'Họ tên người nhận không được vượt quá 100 ký tự.',
            'phone.required' => 'Số điện thoại người nhận không được để trống.',
            'phone.max' => 'Số điện thoại không được vượt quá 10 ký tự.',
            'shipping_address.required' => 'Địa chỉ giao hàng không được để trống.',
            'shipping_address.max' => 'Địa chỉ giao hàng không được vượt quá 500 ký tự.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.Illuminate\Validation\Rules\Enum' => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}
