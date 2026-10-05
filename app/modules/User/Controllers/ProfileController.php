<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\ProfileUpdateRequest;
use App\Modules\User\Services\ProfileService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Display the user's profile form / data.
     */
    public function edit(Request $request): View|JsonResponse
    {
        if ($request->expectsJson()) {
            return $this->successResponse($request->user(), 'Lấy thông tin tài khoản thành công');
        }

        return view('modules.User.pages.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $updatedUser = $this->profileService->updateProfile($request->user(), $request->validated());

        if ($request->expectsJson()) {
            return $this->successResponse($updatedUser, 'Cập nhật thông tin tài khoản thành công');
        }

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $this->profileService->deleteAccount($request->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return $this->successResponse(message: 'Xóa tài khoản thành công');
        }

        return redirect('/');
    }
}
