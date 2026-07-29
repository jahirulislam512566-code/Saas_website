<?php

namespace App\Exceptions\Validation;

use App\Exceptions\BaseException;

class BusinessRuleException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'BUSINESS_RULE_VIOLATION';

    public function __construct(
        string $message = 'Business rule violation',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}