<?php

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Nơi viết các logic nghiệp vụ liên quan đến quản lý người dùng
     */
    public function listUsers(array $filters = [])
    {
        // TODO: Viết logic lấy danh sách người dùng
    }

    public function getUserDetail(int|string $id)
    {
        // TODO: Viết logic lấy thông tin người dùng
    }

    public function createUser(array $data)
    {
        // TODO: Viết logic tạo người dùng
    }

    public function updateUser(int|string $id, array $data)
    {
        // TODO: Viết logic cập nhật người dùng
    }

    public function deleteUser(int|string $id)
    {
        // TODO: Viết logic xóa người dùng
    }
}
