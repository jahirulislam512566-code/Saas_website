<?php

namespace App\Exceptions\Invoice;

use App\Exceptions\BaseException;

class InvoiceGenerationException extends BaseException
{
    protected int $statusCode = 400;
    protected string $errorCode = 'INVOICE_GENERATION_ERROR';

    public function __construct(
        string $message = 'Failed to generate invoice',
        array $errorData = [],
        ?\Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous, $errorData);
    }
}