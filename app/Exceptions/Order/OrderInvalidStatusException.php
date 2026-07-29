<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderInvalidStatusException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'INVALID_ORDER_STATUS';

    public function __construct(
        string $message = 'Invalid order status transition',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}