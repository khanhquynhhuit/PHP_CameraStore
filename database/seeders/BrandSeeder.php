<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Canon',
            'Sony',
            'Nikon',
            'Fujifilm',
            'Panasonic',
            'Olympus',
            'GoPro',
            'Leica',
        ];

        foreach ($brands as $name) {
            Brand::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'is_active' => true,
                ]
            );
        }
    }
}
