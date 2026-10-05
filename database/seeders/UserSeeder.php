<?php

namespace Database\Seeders;

use App\Models\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Lưu ý: Mật khẩu "password" chỉ dùng cho môi trường dev/local testing.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // 1. Quản trị viên hệ thống (Admin)
        User::query()->firstOrCreate(
            ['email' => 'admin@camera.test'],
            [
                'name' => 'Quản trị viên hệ thống',
                'password' => $defaultPassword,
                'phone' => '0901234567',
                'role' => RoleName::ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Nhân viên bán hàng / xử lý đơn (Staff)
        User::query()->firstOrCreate(
            ['email' => 'staff@camera.test'],
            [
                'name' => 'Nhân viên bán hàng',
                'password' => $defaultPassword,
                'phone' => '0902345678',
                'role' => RoleName::STAFF,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Khách hàng chính dùng cho việc test đăng nhập (Customer)
        User::query()->firstOrCreate(
            ['email' => 'customer@camera.test'],
            [
                'name' => 'Nguyễn Văn Khách',
                'password' => $defaultPassword,
                'phone' => '0903456789',
                'role' => RoleName::CUSTOMER,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 4. Thêm 5 khách hàng ngẫu nhiên bằng factory
        User::factory()->count(5)->customer()->create([
            'password' => $defaultPassword,
        ]);
    }
}
