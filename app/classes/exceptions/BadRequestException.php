<?php

namespace API;

use Throwable;

class BadRequestException extends \Exception
{
    protected string $error;
    public function __construct($error = "", $message = "Bad Request", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    public function getError() : string
    {
        return $this->error;
    }
}