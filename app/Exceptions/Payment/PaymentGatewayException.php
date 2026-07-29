<?php

namespace App\Exceptions\Payment;

use App\Exceptions\BaseException;

class PaymentGatewayException extends BaseException
{
    protected int $statusCode = 502;
    protected string $errorCode = 'GATEWAY_ERROR';

    public function __construct(
        string $message = 'Payment gateway error occurred',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}