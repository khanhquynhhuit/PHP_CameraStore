<?php

namespace App\Modules\User\Services;

use App\Exceptions\BadRequestException;
use App\Exceptions\BusinessException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Enums\RoleName;
use App\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Lấy danh sách người dùng có phân trang và lọc theo điều kiện.
     *
     * @param  array<string, mixed>  $filters
     */
    public function listUsers(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->getAll($filters, $perPage);
    }

    /**
     * Lấy thông tin chi tiết một người dùng theo ID.
     *
     * @throws ResourceNotFoundException
     */
    public function getUserDetail(int|string $id): User
    {
        $user = $this->userRepository->findById($id);

        if (! $user) {
            throw new ResourceNotFoundException('Không tìm thấy tài khoản người dùng trong hệ thống!');
        }

        return $user;
    }

    /**
     * Chuyển đổi trạng thái kích hoạt / khóa tài khoản người dùng (toggleStatus).
     *
     * @throws ResourceNotFoundException
     * @throws BusinessException
     */
    public function toggleStatus(int|string $id, ?User $currentUser = null): User
    {
        $user = $this->getUserDetail($id);

        // Quy tắc an toàn: Không cho phép Admin tự khóa tài khoản của chính mình
        if ($currentUser && (int) $currentUser->id === (int) $user->id) {
            throw new BusinessException('Bạn không thể tự khóa tài khoản của chính mình!');
        }

        $newStatus = ! $user->is_active;
        $this->userRepository->updateStatus($user, $newStatus);

        return $user;
    }

    /**
     * Thay đổi vai trò (Role) của người dùng (updateRole).
     *
     * @throws ResourceNotFoundException
     * @throws BadRequestException
     * @throws BusinessException
     */
    public function updateRole(int|string $id, RoleName|string $role, ?User $currentUser = null): User
    {
        $user = $this->getUserDetail($id);

        $roleEnum = $role instanceof RoleName ? $role : RoleName::tryFrom((string) $role);

        if (! $roleEnum) {
            throw new BadRequestException('Vai trò người dùng được chỉ định không hợp lệ!');
        }

        // Quy tắc an toàn: Không cho phép Admin tự hạ quyền của chính mình
        if ($currentUser && (int) $currentUser->id === (int) $user->id && $roleEnum !== RoleName::ADMIN) {
            throw new BusinessException('Bạn không thể tự hạ quyền Quản trị viên của chính mình!');
        }

        // Quy tắc an toàn: Không cho phép hạ quyền Quản trị viên khi chỉ có duy nhất 1 Quản trị viên
        if ($user->isAdmin() && $roleEnum !== RoleName::ADMIN && User::where('role', RoleName::ADMIN)->count() <= 1) {
            throw new BusinessException('Không thể hạ quyền Quản trị viên khi hệ thống chỉ có duy nhất 1 Quản trị viên!');
        }

        $this->userRepository->updateRole($user, $roleEnum);

        return $user;
    }

    /**
     * Tạo tài khoản người dùng mới.
     *
     * @param  array<string, mixed>  $data
     */
    public function createUser(array $data): User
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->create($data);
    }

    /**
     * Cập nhật thông tin tài khoản người dùng.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws ResourceNotFoundException
     */
    public function updateUser(int|string $id, array $data): User
    {
        $user = $this->getUserDetail($id);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepository->update($user, $data);

        return $user->fresh();
    }

    /**
     * Xóa tài khoản người dùng.
     *
     * @throws ResourceNotFoundException
     * @throws BusinessException
     */
    public function deleteUser(int|string $id, ?User $currentUser = null): bool
    {
        $user = $this->getUserDetail($id);

        // Quy tắc an toàn 1: Không cho phép tự xóa tài khoản của chính mình
        if ($currentUser && (int) $currentUser->id === (int) $user->id) {
            throw new BusinessException('Bạn không thể tự xóa tài khoản của chính mình!');
        }

        // Quy tắc an toàn 2: Quản trị viên không được xóa tài khoản khi chỉ có duy nhất 1 tài khoản quản trị viên
        if ($user->isAdmin() && User::where('role', RoleName::ADMIN)->count() <= 1) {
            throw new BusinessException('Không thể xóa tài khoản Quản trị viên khi hệ thống chỉ có duy nhất 1 Quản trị viên!');
        }

        return $this->userRepository->delete($user);
    }
}
