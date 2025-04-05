<?php

namespace App\Exceptions;

use Exception as BaseException;
use Throwable;

abstract class Exception extends BaseException
{
    protected int $statusCode;

    /**
     * Constructs an exception instance.
     *
     * @param  ?Throwable  $previous
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        int $statusCode = 400
    ) {
        parent::__construct($message, $code, $previous);

        $this->statusCode = $statusCode;
    }

    /**
     * Sets HTTP status code.
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Returns the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
