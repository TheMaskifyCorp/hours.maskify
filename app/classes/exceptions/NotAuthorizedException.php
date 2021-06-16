<?php

namespace API;

use Exception;
use Throwable;

/**
 * Class NotAuthorizedException
 * @package API
 */
class NotAuthorizedException extends Exception
{
    protected string $error;

    /**
     * NotAuthorizedException constructor.
     * @param string $error
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
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