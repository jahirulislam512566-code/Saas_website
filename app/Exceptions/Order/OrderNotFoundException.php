<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderNotFoundException extends BaseException
{
    protected int $statusCode = 404;
    protected string $errorCode = 'ORDER_NOT_FOUND';

    public function __construct(
        string $message = 'Order not found',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}