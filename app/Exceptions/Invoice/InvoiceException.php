<?php

namespace App\Exceptions\Invoice;

use App\Exceptions\BaseException;

class InvoiceException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'INVOICE_ERROR';

    public function __construct(
        string $message = 'An invoice error occurred',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}