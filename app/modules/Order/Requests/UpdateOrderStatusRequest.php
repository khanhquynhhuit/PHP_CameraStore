<?php

namespace App\Modules\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', \Illuminate\Validation\Rule::enum(\App\Models\Enums\OrderStatus::class)],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }
}
