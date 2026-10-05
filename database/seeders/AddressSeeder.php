<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::query()->where('role', RoleName::CUSTOMER)->get();

        $sampleLocations = [
            ['province' => 'Hà Nội', 'district' => 'Quận Cầu Giấy', 'ward' => 'Phường Dịch Vọng Hậu', 'detail' => 'Số 144 Xuân Thủy'],
            ['province' => 'Hà Nội', 'district' => 'Quận Hoàn Kiếm', 'ward' => 'Phường Tràng Tiền', 'detail' => 'Số 25 Tràng Tiền'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Quận 1', 'ward' => 'Phường Bến Nghé', 'detail' => 'Số 68 Nguyễn Huệ'],
            ['province' => 'TP. Hồ Chí Minh', 'district' => 'Quận 3', 'ward' => 'Phường Võ Thị Sáu', 'detail' => 'Số 120 Điện Biên Phủ'],
            ['province' => 'Đà Nẵng', 'district' => 'Quận Hải Châu', 'ward' => 'Phường Thạch Thang', 'detail' => 'Số 50 Bạch Đằng'],
            ['province' => 'Cần Thơ', 'district' => 'Quận Ninh Kiều', 'ward' => 'Phường An Cư', 'detail' => 'Số 15 Đại lộ Hòa Bình'],
            ['province' => 'Hải Phòng', 'district' => 'Quận Hồng Bàng', 'ward' => 'Phường Hoàng Văn Thụ', 'detail' => 'Số 88 Đinh Tiên Hoàng'],
        ];

        foreach ($customers as $index => $customer) {
            $loc1 = $sampleLocations[$index % count($sampleLocations)];

            // Địa chỉ 1: Mặc định
            Address::query()->create([
                'user_id' => $customer->id,
                'receiver_name' => $customer->name,
                'phone' => $customer->phone ?? ('09' . fake()->numerify('########')),
                'province' => $loc1['province'],
                'district' => $loc1['district'],
                'ward' => $loc1['ward'],
                'detail' => $loc1['detail'] . ', Tòa nhà Studio',
                'is_default' => true,
            ]);

            // Một số khách hàng có thêm địa chỉ phụ thứ 2 (không mặc định)
            if ($index % 2 === 0) {
                $loc2 = $sampleLocations[($index + 2) % count($sampleLocations)];
                Address::query()->create([
                    'user_id' => $customer->id,
                    'receiver_name' => $customer->name . ' (Cơ quan)',
                    'phone' => $customer->phone ?? ('09' . fake()->numerify('########')),
                    'province' => $loc2['province'],
                    'district' => $loc2['district'],
                    'ward' => $loc2['ward'],
                    'detail' => $loc2['detail'] . ', Văn phòng tầng 5',
                    'is_default' => false,
                ]);
            }
        }
    }
}
