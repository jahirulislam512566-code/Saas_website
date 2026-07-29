<?php

namespace App\Exceptions\Order;

use App\Exceptions\BaseException;

class OrderCancellationException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'ORDER_CANCELLATION_ERROR';

    public function __construct(
        string $message = 'Order cannot be cancelled',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}