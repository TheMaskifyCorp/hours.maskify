<?php

namespace API;

use Throwable;

class NotFoundException extends \Exception
{
    protected string $error;
    public function __construct($error = "", $message = "Not Found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    public function getError() : string
    {
        return $this->error;
    }
}