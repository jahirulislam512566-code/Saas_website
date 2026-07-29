<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseException extends Exception
{
    /**
     * The HTTP status code
     */
    protected int $statusCode = 500;

    /**
     * The error code
     */
    protected string $errorCode = 'GENERAL_ERROR';

    /**
     * Additional error data
     */
    protected array $errorData = [];

    /**
     * Constructor
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        array $errorData = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorData = $errorData;
    }

    /**
     * Get the HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the error code
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Get the error data
     */
    public function getErrorData(): array
    {
        return $this->errorData;
    }

    /**
     * Render the exception as an HTTP response
     */
    public function render(Request $request): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => [
                'code' => $this->getErrorCode(),
                'message' => $this->getMessage(),
                'data' => $this->getErrorData(),
            ],
            'timestamp' => now()->toDateTimeString(),
        ];

        if (config('app.debug')) {
            $response['debug'] = [
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => $this->getTraceAsString(),
            ];
        }

        return response()->json($response, $this->getStatusCode());
    }

    /**
     * Report the exception
     */
    public function report(): void
    {
        // Log the exception
        logger()->error($this->getMessage(), [
            'exception' => get_class($this),
            'code' => $this->getErrorCode(),
            'data' => $this->getErrorData(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ]);
    }
}