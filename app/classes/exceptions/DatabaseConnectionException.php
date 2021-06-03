<?php


namespace API;

use Throwable;

class DatabaseConnectionException extends \Exception
{
    protected string $error;
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