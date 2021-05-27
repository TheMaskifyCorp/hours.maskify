<?php

namespace API;

use Throwable;

class TeapotException extends \Exception
{
    protected string $error;
    public function __construct($error = "", $message = "I'm a teapot", $code = 418, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    public function getError() : string
    {
        return $this->error;
    }
}