<?php

namespace API;

use Throwable;

/**
 * Class BadRequestException
 * @package API
 */
class BadRequestException extends \Exception
{
    protected string $error;

    /**
     * BadRequestException constructor.
     * @param string $error
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
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