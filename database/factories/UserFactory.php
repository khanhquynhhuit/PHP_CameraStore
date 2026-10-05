<?php

namespace Database\Factories;

use App\Models\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Mật khẩu mặc định cho môi trường dev/seed.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => '09' . fake()->numerify('########'),
            'role' => RoleName::CUSTOMER,
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Khởi tạo tài khoản Quản trị viên
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => RoleName::ADMIN,
        ]);
    }

    /**
     * Khởi tạo tài khoản Nhân viên
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => RoleName::STAFF,
        ]);
    }

    /**
     * Khởi tạo tài khoản Khách hàng
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => RoleName::CUSTOMER,
        ]);
    }

    /**
     * Khởi tạo tài khoản bị vô hiệu hóa
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
