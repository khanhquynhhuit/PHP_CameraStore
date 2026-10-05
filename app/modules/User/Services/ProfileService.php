<?php

namespace App\Modules\User\Services;

use App\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Update user profile data.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    /**
     * Delete user account.
     */
    public function deleteAccount(User $user): void
    {
        Auth::logout();
        $user->delete();
    }
}
