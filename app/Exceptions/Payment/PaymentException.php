<?php

namespace App\Exceptions\Payment;

use App\Exceptions\BaseException;

class PaymentException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'PAYMENT_ERROR';

    public function __construct(
        string $message = 'A payment error occurred',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}