<?php


namespace API;

use Exception;
use Throwable;

/**
 * Class DatabaseConnectionException
 * @package API
 */
class DatabaseConnectionException extends Exception
{
    protected string $error;

    /**
     * DatabaseConnectionException constructor.
     * @param string $error
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($error = "", $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = "Error executing request at database";
    }
    public function getError(): string
    {
        return $this->error;
    }
}