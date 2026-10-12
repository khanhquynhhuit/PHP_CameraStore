<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Enums\RoleName;
use App\Models\User;

class AuthRepository
{
    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Find user by ID.
     */
    public function findById(int|string $id): ?User
    {
        return User::find($id);
    }

    /**
     * Create a new customer user (safe from role injection).
     */
    public function createCustomer(array $data): User
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'] ?? null,
            'is_active' => true,
        ]);

        // Gán cứng role là CUSTOMER vì 'role' không nằm trong $fillable
        $user->role = RoleName::CUSTOMER;
        $user->save();

        return $user;
    }

    /**
     * Update user password.
     */
    public function updatePassword(User $user, string $hashedPassword): bool
    {
        return $user->update([
            'password' => $hashedPassword,
        ]);
    }
}
