<?php

namespace Tests\Feature;

use App\Exceptions\BadRequestException;
use App\Exceptions\BusinessException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class GlobalExceptionFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Setup API routes for JSON testing
        Route::get('/api/test-validation-exception', function () {
            throw ValidationException::withMessages([
                'email' => ['Email không hợp lệ.'],
                'password' => ['Mật khẩu phải có ít nhất 8 ký tự.'],
            ]);
        });

        Route::get('/api/test-model-not-found', function () {
            $exception = new ModelNotFoundException();
            $exception->setModel(User::class);
            throw $exception;
        });

        Route::get('/api/test-bad-request', function () {
            throw new BadRequestException('Tham số không hợp lệ', 'INVALID_PARAM', ['field' => 'Thiếu param ID']);
        });

        Route::get('/api/test-unauthorized', function () {
            throw new UnauthorizedException();
        });

        Route::get('/api/test-forbidden', function () {
            throw new ForbiddenException();
        });

        Route::get('/api/test-business-exception', function () {
            throw new BusinessException('Sản phẩm đã hết hàng trong kho', 422, 'OUT_OF_STOCK');
        });

        Route::get('/api/test-server-error', function () {
            throw new \RuntimeException('Lỗi nội bộ server không xác định');
        });

        // Setup Web / MVC routes for Blade & Redirect testing
        Route::get('/web/test-business-exception', function () {
            throw new BusinessException('Mã coupon đã hết lượt sử dụng!');
        });

        Route::get('/web/test-not-found', function () {
            throw new ResourceNotFoundException('Sản phẩm không tồn tại!');
        });

        Route::get('/web/test-forbidden', function () {
            throw new ForbiddenException('Bạn không có quyền quản trị!');
        });
    }

    // ==================== API / JSON TESTS ====================

    public function test_api_formats_validation_exception_correctly(): void
    {
        $response = $this->getJson('/api/test-validation-exception');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'status_code' => 422,
                'message' => 'Dữ liệu cung cấp không hợp lệ',
                'error_code' => 'VALIDATION_ERROR',
                'errors' => [
                    'email' => ['Email không hợp lệ.'],
                    'password' => ['Mật khẩu phải có ít nhất 8 ký tự.'],
                ],
            ]);
    }

    public function test_api_formats_model_not_found_exception_correctly(): void
    {
        $response = $this->getJson('/api/test-model-not-found');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'status_code' => 404,
                'error_code' => 'RESOURCE_NOT_FOUND',
            ])
            ->assertJsonFragment([
                'message' => 'Không tìm thấy dữ liệu User yêu cầu',
            ]);
    }

    public function test_api_formats_bad_request_exception_correctly(): void
    {
        $response = $this->getJson('/api/test-bad-request');

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'status_code' => 400,
                'message' => 'Tham số không hợp lệ',
                'error_code' => 'INVALID_PARAM',
                'errors' => ['field' => 'Thiếu param ID'],
            ]);
    }

    public function test_api_formats_unauthorized_exception_correctly(): void
    {
        $response = $this->getJson('/api/test-unauthorized');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'status_code' => 401,
                'error_code' => 'UNAUTHORIZED',
                'message' => 'Chưa xác thực hoặc phiên đăng nhập đã hết hạn',
            ]);
    }

    public function test_api_formats_forbidden_exception_correctly(): void
    {
        $response = $this->getJson('/api/test-forbidden');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'status_code' => 403,
                'error_code' => 'FORBIDDEN',
                'message' => 'Bạn không có quyền thực hiện hành động này',
            ]);
    }

    public function test_api_formats_business_exception_correctly(): void
    {
        $response = $this->getJson('/api/test-business-exception');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'status_code' => 422,
                'error_code' => 'OUT_OF_STOCK',
                'message' => 'Sản phẩm đã hết hàng trong kho',
            ]);
    }

    public function test_api_formats_generic_server_error_correctly(): void
    {
        $response = $this->getJson('/api/test-server-error');

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'status_code' => 500,
                'error_code' => 'INTERNAL_SERVER_ERROR',
            ]);
    }

    // ==================== MVC / BLADE TESTS ====================

    public function test_mvc_redirects_back_with_error_flash_on_business_exception(): void
    {
        $response = $this->from('/checkout')
            ->get('/web/test-business-exception');

        $response->assertRedirect('/checkout')
            ->assertSessionHas('error', 'Mã coupon đã hết lượt sử dụng!');
    }

    public function test_mvc_renders_404_view_on_resource_not_found(): void
    {
        $response = $this->get('/web/test-not-found');

        $response->assertStatus(404)
            ->assertSee('Không tìm thấy tài nguyên');
    }

    public function test_mvc_renders_403_view_on_forbidden_exception(): void
    {
        $response = $this->get('/web/test-forbidden');

        $response->assertStatus(403)
            ->assertSee('Truy cập bị từ chối');
    }
}
