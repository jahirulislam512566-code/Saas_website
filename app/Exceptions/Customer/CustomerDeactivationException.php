<?php

namespace App\Exceptions\Customer;

use App\Exceptions\BaseException;

class CustomerDeactivationException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'CUSTOMER_DEACTIVATION_ERROR';

    public function __construct(
        string $message = 'Customer cannot be deactivated',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}