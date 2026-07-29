<?php

namespace App\Exceptions\Customer;

use App\Exceptions\BaseException;

class CustomerNotFoundException extends BaseException
{
    protected int $statusCode = 404;
    protected string $errorCode = 'CUSTOMER_NOT_FOUND';

    public function __construct(
        string $message = 'Customer not found',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}