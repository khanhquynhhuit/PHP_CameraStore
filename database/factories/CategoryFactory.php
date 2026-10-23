<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Máy ảnh Mirrorless',
            'Máy ảnh DSLR',
            'Ống kính Mirrorless',
            'Ống kính DSLR',
            'Máy ảnh Compact',
            'Phụ kiện máy ảnh',
            'Chân máy & Gimbal',
        ];

        $name = fake()->unique()->randomElement($categories) . ' ' . fake()->numerify('##');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
        ];
    }
}
