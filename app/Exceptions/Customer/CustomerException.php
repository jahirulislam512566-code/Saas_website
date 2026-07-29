<?php

namespace App\Exceptions\Customer;

use App\Exceptions\BaseException;

class CustomerException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'CUSTOMER_ERROR';

    public function __construct(
        string $message = 'A customer error occurred',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}