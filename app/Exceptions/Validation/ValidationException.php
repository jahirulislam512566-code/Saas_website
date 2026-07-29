<?php

namespace App\Exceptions\Validation;

use App\Exceptions\BaseException;

class ValidationException extends BaseException
{
    protected int $statusCode = 422;
    protected string $errorCode = 'VALIDATION_ERROR';

    public function __construct(
        string $message = 'Validation failed',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }

    /**
     * Create a validation exception with field errors
     */
    public static function withErrors(array $errors): self
    {
        return new static('Validation failed', ['errors' => $errors]);
    }
}