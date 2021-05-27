<?php

namespace API;

use Throwable;

class NotAuthorizedException extends \Exception
{
    protected string $error;
    public function __construct($error = "", $message = "Not Authorized", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->error = $error;
    }

    public function getError() : string
    {
        return $this->error;
    }
}