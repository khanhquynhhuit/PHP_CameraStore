<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BusinessException extends ApiException
{
    public function __construct(
        string $message = 'Thao tác không hợp lệ theo quy tắc nghiệp vụ',
        int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,
        ?string $errorCode = 'BUSINESS_RULE_VIOLATION',
        mixed $errors = null,
        mixed $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $errorCode, $errors, $data, $previous);
    }
}
