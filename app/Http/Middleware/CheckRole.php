<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use App\Exceptions\UnauthorizedException;
use App\Models\Enums\RoleName;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Kiểm tra quyền truy cập theo danh sách role truyền vào (VD: role:admin,staff)
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // 1. Chưa đăng nhập -> Ném lỗi 401 (tự động đẩy về trang login)
        if (! $user) {
            throw new UnauthorizedException('Vui lòng đăng nhập để tiếp tục.');
        }

        // 2. Tài khoản bị khóa (is_active = false) -> Đăng xuất & chặn truy cập
        if (! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw new ForbiddenException('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Quản trị viên.');
        }

        // 3. Lấy giá trị chuỗi của Enum RoleName ('admin', 'staff', 'customer')
        $userRole = $user->role instanceof RoleName
            ? $user->role->value
            : (string) $user->role;

        // 4. Nếu không thuộc các role được phép -> Ném lỗi 403 (tự động render errors/403.blade.php)
        if (! empty($roles) && ! in_array($userRole, $roles, true)) {
            throw new ForbiddenException('Bạn không có quyền truy cập vào khu vực này.');
        }

        return $next($request);
    }
}
