<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiException extends Exception
{
    protected int $statusCode;
    protected ?string $errorCode;
    protected mixed $errors;
    protected mixed $data;

    public function __construct(
        string $message = 'Đã có lỗi xảy ra',
        int $statusCode = Response::HTTP_BAD_REQUEST,
        ?string $errorCode = null,
        mixed $errors = null,
        mixed $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->errors = $errors;
        $this->data = $data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrors(): mixed
    {
        return $this->errors;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
