<?php


namespace PJS\Exception;


use Throwable;

class DeserializingException extends \Exception
{
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}