<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enums\RoleName;
use App\Modules\User\Requests\UpdateUserRoleRequest;
use App\Modules\User\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Hiển thị danh sách tài khoản người dùng có phân trang và bộ lọc.
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only(['role', 'search', 'is_active']);
        $users = $this->userService->listUsers($filters, 10);
        $roles = RoleName::cases();

        if ($request->expectsJson()) {
            return $this->successResponse([
                'users' => $users,
                'roles' => $roles,
            ], 'Lấy danh sách người dùng thành công');
        }

        return view('modules.User.pages.index', compact('users', 'roles', 'filters'));
    }

    /**
     * Chuyển đổi trạng thái kích hoạt / khóa tài khoản người dùng.
     */
    public function toggleStatus(Request $request, string|int $id): RedirectResponse|JsonResponse
    {
        $user = $this->userService->toggleStatus($id, $request->user());

        $actionText = $user->is_active ? 'Mở khóa' : 'Khóa';
        $message = "{$actionText} tài khoản '{$user->name}' thành công!";

        if ($request->expectsJson()) {
            return $this->successResponse($user, $message);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Cập nhật vai trò (Role) cho người dùng.
     */
    public function updateRole(UpdateUserRoleRequest $request, string|int $id): RedirectResponse|JsonResponse
    {
        $user = $this->userService->updateRole($id, $request->validated('role'), $request->user());

        $message = "Đã cập nhật vai trò của tài khoản '{$user->name}' thành {$user->role->label()}!";

        if ($request->expectsJson()) {
            return $this->successResponse($user, $message);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Hiển thị thông tin chi tiết một người dùng.
     */
    public function show(Request $request, string|int $id): View|JsonResponse|RedirectResponse
    {
        $user = $this->userService->getUserDetail($id);

        if ($request->expectsJson()) {
            return $this->successResponse($user, 'Lấy thông tin tài khoản thành công');
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Xóa một tài khoản người dùng.
     */
    public function destroy(Request $request, string|int $id): RedirectResponse|JsonResponse
    {
        $this->userService->deleteUser($id, $request->user());

        $message = 'Đã xóa tài khoản người dùng thành công!';

        if ($request->expectsJson()) {
            return $this->successResponse(null, $message);
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
