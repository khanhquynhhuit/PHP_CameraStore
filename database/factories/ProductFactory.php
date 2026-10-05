<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cameraModels = [
            ['name' => 'Sony Alpha A7 IV', 'sku' => 'SON-A7M4', 'price' => 54990000, 'sensor' => 'Full-frame BSI CMOS 33MP', 'mp' => 33, 'mount' => 'Sony E'],
            ['name' => 'Sony Alpha A7R V', 'sku' => 'SON-A7R5', 'price' => 84990000, 'sensor' => 'Full-frame Exmor R BSI 61MP', 'mp' => 61, 'mount' => 'Sony E'],
            ['name' => 'Canon EOS R6 Mark II', 'sku' => 'CAN-R6M2', 'price' => 59990000, 'sensor' => 'Full-frame CMOS 24.2MP', 'mp' => 24.2, 'mount' => 'Canon RF'],
            ['name' => 'Canon EOS R5', 'sku' => 'CAN-R5', 'price' => 81990000, 'sensor' => 'Full-frame CMOS 45MP', 'mp' => 45, 'mount' => 'Canon RF'],
            ['name' => 'Nikon Z8', 'sku' => 'NIK-Z8', 'price' => 92990000, 'sensor' => 'Full-frame Stacked CMOS 45.7MP', 'mp' => 45.7, 'mount' => 'Nikon Z'],
            ['name' => 'Nikon Z6 III', 'sku' => 'NIK-Z6M3', 'price' => 62990000, 'sensor' => 'Full-frame Partially-Stacked 24.5MP', 'mp' => 24.5, 'mount' => 'Nikon Z'],
            ['name' => 'Fujifilm X-T5', 'sku' => 'FUJ-XT5', 'price' => 41990000, 'sensor' => 'APS-C X-Trans CMOS 5 HR 40.2MP', 'mp' => 40.2, 'mount' => 'Fujifilm X'],
            ['name' => 'Fujifilm X100VI', 'sku' => 'FUJ-X100VI', 'price' => 46990000, 'sensor' => 'APS-C X-Trans CMOS 5 HR 40.2MP', 'mp' => 40.2, 'mount' => 'Fixed 23mm F2'],
            ['name' => 'Panasonic Lumix S5 II', 'sku' => 'PAN-S5M2', 'price' => 45990000, 'sensor' => 'Full-frame CMOS 24.2MP', 'mp' => 24.2, 'mount' => 'Leica L'],
            ['name' => 'Leica Q3', 'sku' => 'LEI-Q3', 'price' => 155000000, 'sensor' => 'Full-frame BSI CMOS 60MP', 'mp' => 60, 'mount' => 'Fixed Summilux 28mm F1.7'],
        ];

        $item = fake()->randomElement($cameraModels);
        $name = $item['name'] . ' ' . fake()->numerify('V#');
        $price = $item['price'];
        $originalPrice = fake()->boolean(60) ? $price + fake()->numberBetween(1, 5) * 1000000 : null;

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'sku' => $item['sku'] . '-' . strtoupper(Str::random(4)),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(4)),
            'price' => $price,
            'original_price' => $originalPrice,
            'stock' => fake()->numberBetween(5, 50),
            'description' => fake()->paragraphs(3, true),
            'specs' => [
                'sensor_type' => $item['sensor'],
                'resolution_mp' => (string) $item['mp'],
                'mount' => $item['mount'],
                'iso_range' => '100 - 51,200 (mở rộng 50 - 204,800)',
                'video' => '4K UHD 60fps 10-bit 4:2:2',
                'screen' => '3.0 inch LCD cảm ứng lật xoay đa góc',
                'weight_g' => fake()->numberBetween(450, 950),
                'condition' => 'Mới 100% Chính Hãng',
            ],
            'warranty_months' => fake()->randomElement([12, 24]),
            'status' => ProductStatus::ACTIVE,
            'created_by' => null,
        ];
    }

    /**
     * Sản phẩm hết hàng (stock = 0)
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Sản phẩm bị ẩn
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProductStatus::HIDDEN,
        ]);
    }
}
