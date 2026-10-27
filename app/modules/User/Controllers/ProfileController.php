<?php

namespace App\Modules\User\Controllers;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Modules\User\Requests\ProfileUpdateRequest;
use App\Modules\User\Requests\StoreAddressRequest;
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
     * Hiển thị trang quản lý hồ sơ cá nhân và danh sách địa chỉ nhận hàng.
     */
    public function edit(Request $request): View|JsonResponse
    {
        $user = $request->user();
        $addresses = $user->addresses()->latest('is_default')->latest('id')->get();

        if ($request->expectsJson()) {
            return $this->successResponse([
                'user' => $user,
                'addresses' => $addresses,
            ], 'Lấy thông tin tài khoản thành công');
        }

        return view('modules.User.pages.edit', [
            'user' => $user,
            'addresses' => $addresses,
        ]);
    }

    /**
     * Cập nhật thông tin cơ bản: họ tên, email, số điện thoại.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $updatedUser = $this->profileService->updateProfile($request->user(), $request->validated());

        if ($request->expectsJson()) {
            return $this->successResponse($updatedUser, 'Cập nhật thông tin tài khoản thành công');
        }

        return redirect()->route('profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    /**
     * Thêm địa chỉ nhận hàng mới.
     */
    public function storeAddress(StoreAddressRequest $request): RedirectResponse|JsonResponse
    {
        if (! $request->user()->isCustomer()) {
            throw new BusinessException('Chức năng sổ địa chỉ nhận hàng chỉ áp dụng cho tài khoản Khách hàng.');
        }

        $address = $this->profileService->addAddress($request->user(), $request->validated());

        if ($request->expectsJson()) {
            return $this->successResponse($address, 'Thêm địa chỉ giao hàng thành công', 201);
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Đã thêm địa chỉ giao hàng mới thành công!');
    }

    /**
     * Đặt địa chỉ làm mặc định.
     */
    public function setDefaultAddress(Request $request, int|string $id): RedirectResponse|JsonResponse
    {
        $address = $this->profileService->setDefaultAddress($request->user(), $id);

        if ($request->expectsJson()) {
            return $this->successResponse($address, 'Đã đặt địa chỉ làm mặc định');
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Đã đổi địa chỉ giao hàng mặc định thành công!');
    }

    /**
     * Xóa một địa chỉ nhận hàng.
     */
    public function destroyAddress(Request $request, int|string $id): RedirectResponse|JsonResponse
    {
        $this->profileService->deleteAddress($request->user(), $id);

        if ($request->expectsJson()) {
            return $this->successResponse(null, 'Đã xóa địa chỉ thành công');
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Đã xóa địa chỉ nhận hàng thành công!');
    }

    /**
     * Xóa vĩnh viễn tài khoản người dùng.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu xác nhận.',
            'password.current_password' => 'Mật khẩu xác nhận không chính xác.',
        ]);

        $this->profileService->deleteAccount($request->user());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return $this->successResponse(message: 'Xóa tài khoản thành công');
        }

        return redirect('/')->with('success', 'Tài khoản của bạn đã được xóa khỏi hệ thống.');
    }
}
