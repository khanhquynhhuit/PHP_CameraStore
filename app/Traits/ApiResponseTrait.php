<?php

namespace App\Traits;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    /**
     * Return a standardized success JSON response.
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Thao tác thành công',
        int $statusCode = Response::HTTP_OK,
        array $extra = []
    ): JsonResponse {
        return ApiResponse::success($data, $message, $statusCode, $extra);
    }

    /**
     * Return a standardized error JSON response.
     */
    protected function errorResponse(
        string $message = 'Đã có lỗi xảy ra',
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        mixed $errors = null,
        ?string $errorCode = null,
        mixed $data = null
    ): JsonResponse {
        return ApiResponse::error($message, $statusCode, $errors, $errorCode, $data);
    }

    /**
     * Return a standardized paginated JSON response.
     */
    protected function paginateResponse(
        LengthAwarePaginator $paginator,
        string $message = 'Lấy danh sách thành công',
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return ApiResponse::paginate($paginator, $message, $statusCode);
    }
}
