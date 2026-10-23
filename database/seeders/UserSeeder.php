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

        // 1. Quản trị viên hệ thống chuẩn theo đề bài (Admin)
        User::query()->firstOrCreate(
            ['email' => 'admin@camerastore.vn'],
            [
                'name' => 'Quản trị viên hệ thống',
                'password' => $defaultPassword,
                'phone' => '0901234567',
                'role' => RoleName::ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Nhân viên bán hàng chuẩn theo đề bài (Staff)
        User::query()->firstOrCreate(
            ['email' => 'staff@camerastore.vn'],
            [
                'name' => 'Nhân viên bán hàng',
                'password' => $defaultPassword,
                'phone' => '0902345678',
                'role' => RoleName::STAFF,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Khách hàng chuẩn theo đề bài (Customer)
        User::query()->firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Nguyễn Văn Khách',
                'password' => $defaultPassword,
                'phone' => '0903456789',
                'role' => RoleName::CUSTOMER,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Giữ lại các tài khoản @camera.test cho môi trường local/legacy seeders
        User::query()->firstOrCreate(
            ['email' => 'admin@camera.test'],
            [
                'name' => 'Admin Test',
                'password' => $defaultPassword,
                'phone' => '0901234568',
                'role' => RoleName::ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'staff@camera.test'],
            [
                'name' => 'Staff Test',
                'password' => $defaultPassword,
                'phone' => '0902345679',
                'role' => RoleName::STAFF,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'customer@camera.test'],
            [
                'name' => 'Customer Test',
                'password' => $defaultPassword,
                'phone' => '0903456780',
                'role' => RoleName::CUSTOMER,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 4. Thêm 5 khách hàng cố định tên tiếng Việt
        $customers = [
            ['name' => 'Trần Thị Mai',   'email' => 'mai.tran@gmail.com',  'phone' => '0911111111'],
            ['name' => 'Lê Hoàng Nam',   'email' => 'nam.le@gmail.com',    'phone' => '0922222222'],
            ['name' => 'Phạm Minh Tuấn', 'email' => 'tuan.pham@gmail.com', 'phone' => '0933333333'],
            ['name' => 'Võ Ngọc Hân',    'email' => 'han.vo@gmail.com',    'phone' => '0944444444'],
            ['name' => 'Đặng Quốc Bảo',  'email' => 'bao.dang@gmail.com',  'phone' => '0955555555'],
        ];

        foreach ($customers as $c) {
            User::query()->firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => $defaultPassword,
                    'phone' => $c['phone'],
                    'role' => RoleName::CUSTOMER,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
