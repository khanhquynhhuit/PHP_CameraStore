<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BadRequestException extends ApiException
{
    public function __construct(
        string $message = 'Yêu cầu không hợp lệ',
        ?string $errorCode = 'BAD_REQUEST',
        mixed $errors = null,
        mixed $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, Response::HTTP_BAD_REQUEST, $errorCode, $errors, $data, $previous);
    }
}
