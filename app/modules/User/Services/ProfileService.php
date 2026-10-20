<?php

namespace App\Modules\User\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Address;
use App\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Cập nhật thông tin hồ sơ người dùng.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    /**
     * Thêm địa chỉ nhận hàng mới cho người dùng.
     *
     * @param  array<string, mixed>  $data
     */
    public function addAddress(User $user, array $data): Address
    {
        $isFirst = $user->addresses()->count() === 0;
        $isDefault = $isFirst || ! empty($data['is_default']);

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        return $user->addresses()->create([
            'receiver_name' => $data['receiver_name'],
            'phone' => $data['phone'],
            'province' => $data['province'],
            'district' => $data['district'],
            'ward' => $data['ward'],
            'detail' => $data['detail'],
            'is_default' => $isDefault,
        ]);
    }

    /**
     * Đặt một địa chỉ làm địa chỉ mặc định.
     *
     * @throws ResourceNotFoundException
     */
    public function setDefaultAddress(User $user, int|string $addressId): Address
    {
        $address = $user->addresses()->where('id', $addressId)->first();

        if (! $address) {
            throw new ResourceNotFoundException('Không tìm thấy địa chỉ được chỉ định!');
        }

        $user->addresses()->update(['is_default' => false]);
        $address->is_default = true;
        $address->save();

        return $address;
    }

    /**
     * Xóa địa chỉ nhận hàng của người dùng.
     *
     * @throws ResourceNotFoundException
     */
    public function deleteAddress(User $user, int|string $addressId): bool
    {
        $address = $user->addresses()->where('id', $addressId)->first();

        if (! $address) {
            throw new ResourceNotFoundException('Không tìm thấy địa chỉ cần xóa!');
        }

        $wasDefault = $address->is_default;
        $deleted = (bool) $address->delete();

        // Nếu vừa xóa địa chỉ mặc định, tự động gán địa chỉ còn lại đầu tiên làm mặc định
        if ($wasDefault) {
            $user->addresses()->first()?->update(['is_default' => true]);
        }

        return $deleted;
    }

    /**
     * Xóa vĩnh viễn tài khoản người dùng.
     */
    public function deleteAccount(User $user): void
    {
        Auth::logout();
        $user->delete();
    }
}
