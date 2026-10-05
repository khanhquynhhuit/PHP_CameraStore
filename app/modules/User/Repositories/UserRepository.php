<?php

namespace App\Modules\User\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Nơi viết các truy vấn Database liên quan đến User
     */
    public function getAll(array $filters = [])
    {
        // TODO: Viết query lấy danh sách người dùng
    }

    public function findById(int|string $id)
    {
        // TODO: Viết query tìm người dùng theo ID
    }

    public function findByEmail(string $email)
    {
        // TODO: Viết query tìm người dùng theo Email
    }

    public function create(array $data)
    {
        // TODO: Viết query tạo người dùng
    }

    public function update(User|int|string $user, array $data)
    {
        // TODO: Viết query cập nhật người dùng
    }

    public function delete(User|int|string $user)
    {
        // TODO: Viết query xóa người dùng
    }
}
