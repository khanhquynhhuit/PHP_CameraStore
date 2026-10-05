<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class GlobalExceptionHandler
{
    /**
     * Render an exception into an HTTP Response (Blade View / Redirect for MVC or JSON for API/AJAX).
     */
    public static function render(Throwable $e, Request $request): Response|JsonResponse|RedirectResponse|null
    {
        // 1. Trường hợp Request là API / AJAX / JSON -> Trả về JSON chuẩn
        if (self::shouldReturnJson($request)) {
            return self::renderJsonResponse($e);
        }

        // 2. Trường hợp Request là MVC Web (Blade Views / HTML) -> Trả về View / Redirect
        return self::renderMvcResponse($e, $request);
    }

    /**
     * Xử lý lỗi trả về cho Giao diện MVC (Blade Views & Flash Redirects)
     */
    protected static function renderMvcResponse(Throwable $e, Request $request): Response|RedirectResponse|null
    {
        // Lỗi Validation -> Để Laravel tự động redirect back kèm $errors
        if ($e instanceof ValidationException) {
            return null;
        }

        // Lỗi xác thực (Chưa đăng nhập) -> Chuyển hướng về trang Login kèm thông báo
        if ($e instanceof AuthenticationException) {
            return redirect()->guest(route('login'))
                ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Lỗi nghiệp vụ (BusinessException / BadRequestException) -> Redirect back kèm Flash message đỏ
        if ($e instanceof ApiException) {
            if ($e instanceof ResourceNotFoundException) {
                return self::renderErrorView(404, $e->getMessage());
            }

            if ($e instanceof ForbiddenException) {
                return self::renderErrorView(403, $e->getMessage());
            }

            // Mặc định redirect back kèm session('error')
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage())
                ->with('error_code', $e->getErrorCode());
        }

        // Lỗi 403 Forbidden (Không có quyền)
        if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
            return self::renderErrorView(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        // Lỗi 404 Không tìm thấy trang hoặc Model
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            $message = $e instanceof ModelNotFoundException
                ? self::getErrorMessage($e, 404)
                : 'Trang bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.';

            return self::renderErrorView(404, $message);
        }

        // Lỗi 405 Method Not Allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            return self::renderErrorView(405, 'Phương thức gửi yêu cầu không được hỗ trợ.');
        }

        // Lỗi 429 Quá nhiều yêu cầu
        if ($e instanceof ThrottleRequestsException || $e instanceof TooManyRequestsHttpException) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Bạn đã thao tác quá nhanh. Vui lòng thử lại sau giây lát.');
        }

        // Nếu đang ở môi trường Local/Debug -> Trả về null để Laravel hiển thị trang Debug chi tiết (Ignition/Error page)
        if (config('app.debug', false)) {
            return null;
        }

        // Môi trường Production khi gặp lỗi 500 bất ngờ
        return self::renderErrorView(500, 'Đã xảy ra lỗi trên hệ thống. Vui lòng liên hệ quản trị viên.');
    }

    /**
     * Render view lỗi HTML tương ứng trong resources/views/errors/
     */
    protected static function renderErrorView(int $statusCode, string $message): HttpResponse
    {
        $viewName = "errors.{$statusCode}";

        if (view()->exists($viewName)) {
            return response()->view($viewName, ['message' => $message, 'statusCode' => $statusCode], $statusCode);
        }

        if (view()->exists('errors.minimal')) {
            return response()->view('errors.minimal', ['message' => $message, 'code' => $statusCode], $statusCode);
        }

        // Fallback HTML cơ bản nếu chưa tạo file view
        return response(
            "<div style='font-family:sans-serif; text-align:center; padding:50px;'><h1>{$statusCode}</h1><p>{$message}</p><a href='/'>Quay về trang chủ</a></div>",
            $statusCode
        );
    }

    /**
     * Xử lý lỗi trả về định dạng JSON (cho API/AJAX)
     */
    protected static function renderJsonResponse(Throwable $e): JsonResponse
    {
        $statusCode = self::getStatusCode($e);
        $errorCode = self::getErrorCode($e, $statusCode);
        $message = self::getErrorMessage($e, $statusCode);
        $errors = self::getErrorDetails($e);
        $data = self::getErrorData($e);
        $debug = self::getDebugInfo($e);

        return ApiResponse::error(
            message: $message,
            statusCode: $statusCode,
            errors: $errors,
            errorCode: $errorCode,
            data: $data,
            debug: $debug
        );
    }

    /**
     * Kiểm tra xem Request có yêu cầu trả về JSON / AJAX hay không
     */
    public static function shouldReturnJson(Request $request): bool
    {
        return $request->is('api/*')
            || $request->expectsJson()
            || $request->wantsJson()
            || $request->ajax()
            || $request->header('Accept') === 'application/json';
    }

    /**
     * Lấy HTTP Status Code từ Exception
     */
    protected static function getStatusCode(Throwable $e): int
    {
        if ($e instanceof ApiException) {
            return $e->getStatusCode();
        }

        if ($e instanceof ValidationException) {
            return Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        if ($e instanceof AuthenticationException) {
            return Response::HTTP_UNAUTHORIZED;
        }

        if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
            return Response::HTTP_FORBIDDEN;
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return Response::HTTP_NOT_FOUND;
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return Response::HTTP_METHOD_NOT_ALLOWED;
        }

        if ($e instanceof ThrottleRequestsException || $e instanceof TooManyRequestsHttpException) {
            return Response::HTTP_TOO_MANY_REQUESTS;
        }

        if ($e instanceof HttpExceptionInterface) {
            return $e->getStatusCode();
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Lấy mã định danh lỗi (Error Code)
     */
    protected static function getErrorCode(Throwable $e, int $statusCode): string
    {
        if ($e instanceof ApiException && $e->getErrorCode() !== null) {
            return $e->getErrorCode();
        }

        return match ($statusCode) {
            Response::HTTP_BAD_REQUEST => 'BAD_REQUEST',
            Response::HTTP_UNAUTHORIZED => 'UNAUTHORIZED',
            Response::HTTP_FORBIDDEN => 'FORBIDDEN',
            Response::HTTP_NOT_FOUND => 'RESOURCE_NOT_FOUND',
            Response::HTTP_METHOD_NOT_ALLOWED => 'METHOD_NOT_ALLOWED',
            Response::HTTP_UNPROCESSABLE_ENTITY => 'VALIDATION_ERROR',
            Response::HTTP_TOO_MANY_REQUESTS => 'TOO_MANY_REQUESTS',
            default => ($e instanceof QueryException || $e instanceof PDOException)
                ? 'DATABASE_ERROR'
                : 'INTERNAL_SERVER_ERROR',
        };
    }

    /**
     * Lấy thông báo lỗi thân thiện cho người dùng
     */
    protected static function getErrorMessage(Throwable $e, int $statusCode): string
    {
        if ($e instanceof ApiException) {
            return $e->getMessage();
        }

        if ($e instanceof ValidationException) {
            return 'Dữ liệu cung cấp không hợp lệ';
        }

        if ($e instanceof AuthenticationException) {
            return 'Chưa xác thực hoặc phiên đăng nhập đã hết hạn';
        }

        if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
            return 'Bạn không có quyền thực hiện hành động này';
        }

        if ($e instanceof ModelNotFoundException) {
            $modelNames = explode('\\', (string) $e->getModel());
            $model = end($modelNames);
            return "Không tìm thấy dữ liệu {$model} yêu cầu";
        }

        if ($e instanceof NotFoundHttpException) {
            $prev = $e->getPrevious();
            if ($prev instanceof ModelNotFoundException) {
                return self::getErrorMessage($prev, Response::HTTP_NOT_FOUND);
            }
            return 'Đường dẫn hoặc tài nguyên không tồn tại';
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return 'Phương thức HTTP không được hỗ trợ cho đường dẫn này';
        }

        if ($e instanceof ThrottleRequestsException || $e instanceof TooManyRequestsHttpException) {
            return 'Quá nhiều yêu cầu. Vui lòng thử lại sau';
        }

        if ($e instanceof QueryException || $e instanceof PDOException) {
            return config('app.debug')
                ? $e->getMessage()
                : 'Đã xảy ra lỗi cơ sở dữ liệu. Vui lòng liên hệ quản trị viên';
        }

        if ($e instanceof HttpExceptionInterface) {
            return $e->getMessage() ?: (Response::$statusTexts[$statusCode] ?? 'Lỗi HTTP');
        }

        return config('app.debug')
            ? $e->getMessage()
            : 'Đã xảy ra lỗi trên hệ thống. Vui lòng thử lại sau';
    }

    /**
     * Lấy chi tiết lỗi validation
     */
    protected static function getErrorDetails(Throwable $e): mixed
    {
        if ($e instanceof ApiException) {
            return $e->getErrors();
        }

        if ($e instanceof ValidationException) {
            return $e->errors();
        }

        return null;
    }

    /**
     * Lấy dữ liệu đính kèm lỗi nếu có
     */
    protected static function getErrorData(Throwable $e): mixed
    {
        if ($e instanceof ApiException) {
            return $e->getData();
        }

        return null;
    }

    /**
     * Thông tin gỡ lỗi khi APP_DEBUG=true
     */
    protected static function getDebugInfo(Throwable $e): ?array
    {
        if (!config('app.debug', false)) {
            return null;
        }

        return [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->take(10)->map(fn($item) => [
                'file' => $item['file'] ?? null,
                'line' => $item['line'] ?? null,
                'function' => $item['function'] ?? null,
                'class' => $item['class'] ?? null,
            ])->all(),
        ];
    }
}
