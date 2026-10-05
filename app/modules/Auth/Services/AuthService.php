<?php

namespace App\Modules\Auth\Services;

use App\Exceptions\BadRequestException;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use App\Modules\Auth\Repositories\AuthRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        protected AuthRepository $authRepository
    ) {}

    /**
     * Register a new user and trigger events.
     */
    public function register(array $data): User
    {
        $user = $this->authRepository->createCustomer([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    /**
     * Update authenticated user's password.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $this->authRepository->updatePassword($user, Hash::make($newPassword));
    }

    /**
     * Send password reset link to user's email.
     */
    public function sendResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new BadRequestException(__($status), 'PASSWORD_RESET_FAILED');
        }

        return __($status);
    }

    /**
     * Reset password with token.
     */
    public function resetPassword(array $credentials): string
    {
        $status = Password::reset(
            $credentials,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new BadRequestException(__($status), 'PASSWORD_RESET_FAILED');
        }

        return __($status);
    }

    /**
     * Logout current session.
     */
    public function logout(): void
    {
        Auth::guard('web')->logout();
    }
}
