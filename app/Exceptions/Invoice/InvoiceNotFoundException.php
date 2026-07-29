<?php

namespace App\Exceptions\Invoice;

use App\Exceptions\BaseException;

class InvoiceNotFoundException extends BaseException
{
    protected int $statusCode = 404;
    protected string $errorCode = 'INVOICE_NOT_FOUND';

    public function __construct(
        string $message = 'Invoice not found',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}