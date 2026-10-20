<?php

namespace App\Modules\User\Repositories;

use App\Models\Enums\RoleName;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Lấy danh sách người dùng có phân trang và áp dụng các bộ lọc (role, search, is_active).
     *
     * @param  array<string, mixed>  $filters
     */
    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = User::query()->withCount('orders');

        // Lọc theo vai trò (Role)
        if (! empty($filters['role'])) {
            $roleValue = $filters['role'] instanceof RoleName
                ? $filters['role']->value
                : (string) $filters['role'];

            $query->where('role', $roleValue);
        }

        // Lọc theo trạng thái kích hoạt (is_active)
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
        }

        // Tìm kiếm đa tiêu chí: Họ tên, Email, Số điện thoại
        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Tìm người dùng theo ID.
     */
    public function findById(int|string $id): ?User
    {
        return User::find($id);
    }

    /**
     * Tìm người dùng theo địa chỉ Email.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Cập nhật trạng thái kích hoạt / khóa tài khoản.
     */
    public function updateStatus(User $user, bool $isActive): bool
    {
        $user->is_active = $isActive;

        return $user->save();
    }

    /**
     * Cập nhật vai trò người dùng (Admin, Staff, Customer).
     */
    public function updateRole(User $user, RoleName|string $role): bool
    {
        $user->role = $role instanceof RoleName ? $role : RoleName::from($role);

        return $user->save();
    }

    /**
     * Tạo tài khoản người dùng mới.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (! empty($data['role'])) {
            $user->role = $data['role'] instanceof RoleName
                ? $data['role']
                : RoleName::from($data['role']);
        }

        $user->save();

        return $user;
    }

    /**
     * Cập nhật thông tin người dùng.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(User|int|string $user, array $data): bool
    {
        $userModel = $user instanceof User ? $user : $this->findById($user);

        if (! $userModel) {
            return false;
        }

        $userModel->fill($data);

        if (! empty($data['role'])) {
            $userModel->role = $data['role'] instanceof RoleName
                ? $data['role']
                : RoleName::from($data['role']);
        }

        return $userModel->save();
    }

    /**
     * Xóa tài khoản người dùng khỏi hệ thống.
     */
    public function delete(User|int|string $user): bool
    {
        $userModel = $user instanceof User ? $user : $this->findById($user);

        return (bool) $userModel?->delete();
    }
}
