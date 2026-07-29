<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'ORDER_ERROR';

    public function __construct(
        string $message = 'An order error occurred',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}