<?php

namespace App\Exceptions\Payment;

use App\Exceptions\BaseException;

class PaymentFailedException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'PAYMENT_FAILED';

    public function __construct(
        string $message = 'Payment processing failed',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}