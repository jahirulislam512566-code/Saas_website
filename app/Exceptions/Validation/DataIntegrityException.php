<?php

namespace App\Exceptions\Validation;

use App\Exceptions\BaseException;

class DataIntegrityException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'DATA_INTEGRITY_ERROR';

    public function __construct(
        string $message = 'Data integrity violation',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}