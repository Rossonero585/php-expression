<?php

namespace Rossonero585\PhpExpression\Exceptions;


class ExecutionException extends \Exception
{
    /**
     * ExecutionException constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}