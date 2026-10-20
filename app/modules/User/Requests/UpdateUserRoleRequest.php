<?php

namespace App\Modules\User\Requests;

use App\Models\Enums\RoleName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRoleRequest extends FormRequest
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
            'role' => ['required', new Enum(RoleName::class)],
        ];
    }

    /**
     * Custom validation messages in Vietnamese.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'role.required' => 'Vui lòng chọn vai trò người dùng.',
            'role.Illuminate\Validation\Rules\Enum' => 'Vai trò được chọn không hợp lệ.',
        ];
    }
}
