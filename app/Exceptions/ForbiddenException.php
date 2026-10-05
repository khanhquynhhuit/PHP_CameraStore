<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ForbiddenException extends ApiException
{
    public function __construct(
        string $message = 'Bạn không có quyền thực hiện hành động này',
        ?string $errorCode = 'FORBIDDEN',
        mixed $errors = null,
        mixed $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, Response::HTTP_FORBIDDEN, $errorCode, $errors, $data, $previous);
    }
}
