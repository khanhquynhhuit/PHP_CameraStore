<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UnauthorizedException extends ApiException
{
    public function __construct(
        string $message = 'Chưa xác thực hoặc phiên đăng nhập đã hết hạn',
        ?string $errorCode = 'UNAUTHORIZED',
        mixed $errors = null,
        mixed $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, Response::HTTP_UNAUTHORIZED, $errorCode, $errors, $data, $previous);
    }
}
