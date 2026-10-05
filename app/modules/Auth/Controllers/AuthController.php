<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\ForgotPasswordRequest;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Requests\ResetPasswordRequest;
use App\Modules\Auth\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Show the login view.
     */
    public function showLogin(): View
    {
        return view('modules.Auth.pages.login');
    }

    /**
     * Handle login request.
     */
    public function login(LoginRequest $request): JsonResponse|RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return $this->successResponse(
                data: ['user' => $request->user()],
                message: 'Đăng nhập thành công'
            );
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Show registration view.
     */
    public function showRegister(): View
    {
        return view('modules.Auth.pages.register');
    }

    /**
     * Handle registration request.
     */
    public function register(RegisterRequest $request): JsonResponse|RedirectResponse
    {
        $user = $this->authService->register($request->validated());

        if ($request->expectsJson()) {
            return $this->successResponse(
                data: ['user' => $user],
                message: 'Đăng ký tài khoản thành công',
                statusCode: 201
            );
        }

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return $this->successResponse(message: 'Đăng xuất thành công');
        }

        return redirect('/');
    }

    /**
     * Show forgot password view.
     */
    public function showForgotPassword(): View
    {
        return view('modules.Auth.pages.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function sendResetLink(ForgotPasswordRequest $request): JsonResponse|RedirectResponse
    {
        $status = $this->authService->sendResetLink($request->email);

        if ($request->expectsJson()) {
            return $this->successResponse(message: $status);
        }

        return back()->with('status', $status);
    }

    /**
     * Show reset password view.
     */
    public function showResetPassword(Request $request): View
    {
        return view('modules.Auth.pages.reset-password', ['request' => $request]);
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse|RedirectResponse
    {
        $status = $this->authService->resetPassword(
            $request->only('email', 'password', 'password_confirmation', 'token')
        );

        if ($request->expectsJson()) {
            return $this->successResponse(message: $status);
        }

        return redirect()->route('login')->with('status', $status);
    }

    /**
     * Show confirm password view.
     */
    public function showConfirmPassword(): View
    {
        return view('modules.Auth.pages.confirm-password');
    }

    /**
     * Handle confirm password request.
     */
    public function confirmPassword(Request $request): JsonResponse|RedirectResponse
    {
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        if ($request->expectsJson()) {
            return $this->successResponse(message: 'Xác nhận mật khẩu thành công');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Show email verification notice prompt.
     */
    public function showVerifyEmailPrompt(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard', absolute: false))
            : view('modules.Auth.pages.verify-email');
    }

    /**
     * Handle email verification link.
     */
    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse|JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return $this->successResponse(message: 'Email đã được xác thực trước đó');
            }
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if ($request->expectsJson()) {
            return $this->successResponse(message: 'Xác thực email thành công');
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }

    /**
     * Resend email verification notification.
     */
    public function sendEmailVerificationNotification(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        if ($request->expectsJson()) {
            return $this->successResponse(message: 'Đã gửi lại email xác thực');
        }

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Handle update password.
     */
    public function updatePassword(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'current_password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $this->authService->updatePassword($request->user(), $validated['password']);

        if ($request->expectsJson()) {
            return $this->successResponse(message: 'Cập nhật mật khẩu thành công');
        }

        return back()->with('status', 'password-updated');
    }
}
