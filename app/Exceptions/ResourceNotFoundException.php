<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResourceNotFoundException extends ApiException
{
    public function __construct(
        string $message = 'Không tìm thấy tài nguyên yêu cầu',
        ?string $errorCode = 'RESOURCE_NOT_FOUND',
        mixed $errors = null,
        mixed $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, Response::HTTP_NOT_FOUND, $errorCode, $errors, $data, $previous);
    }
}
