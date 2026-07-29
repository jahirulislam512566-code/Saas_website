<?php

namespace App\Exceptions\Payment;

use App\Exceptions\BaseException;

class PaymentRefundException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'REFUND_FAILED';

    public function __construct(
        string $message = 'Payment refund failed',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}