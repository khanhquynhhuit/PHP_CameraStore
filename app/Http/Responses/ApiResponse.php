<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Return a standardized success JSON response.
     */
    public static function success(
        mixed $data = null,
        string $message = 'Thao tác thành công',
        int $statusCode = Response::HTTP_OK,
        array $extra = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($extra)) {
            $response = array_merge($response, $extra);
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized error JSON response.
     */
    public static function error(
        string $message = 'Đã có lỗi xảy ra',
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        mixed $errors = null,
        ?string $errorCode = null,
        mixed $data = null,
        ?array $debug = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'status_code' => $statusCode,
            'message' => $message,
            'error_code' => $errorCode ?? self::getDefaultErrorCode($statusCode),
            'errors' => $errors,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ];

        if ($debug !== null && config('app.debug', false)) {
            $response['debug'] = $debug;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized paginated JSON response.
     */
    public static function paginate(
        LengthAwarePaginator $paginator,
        string $message = 'Lấy danh sách thành công',
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return self::success(
            data: $paginator->items(),
            message: $message,
            statusCode: $statusCode,
            extra: [
                'pagination' => [
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                    'has_more_pages' => $paginator->hasMorePages(),
                ]
            ]
        );
    }

    /**
     * Map HTTP status code to default readable error code.
     */
    private static function getDefaultErrorCode(int $statusCode): string
    {
        return match ($statusCode) {
            Response::HTTP_BAD_REQUEST => 'BAD_REQUEST',
            Response::HTTP_UNAUTHORIZED => 'UNAUTHORIZED',
            Response::HTTP_FORBIDDEN => 'FORBIDDEN',
            Response::HTTP_NOT_FOUND => 'NOT_FOUND',
            Response::HTTP_METHOD_NOT_ALLOWED => 'METHOD_NOT_ALLOWED',
            Response::HTTP_UNPROCESSABLE_ENTITY => 'VALIDATION_ERROR',
            Response::HTTP_TOO_MANY_REQUESTS => 'TOO_MANY_REQUESTS',
            default => 'INTERNAL_SERVER_ERROR',
        };
    }
}
