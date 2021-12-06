<?php

namespace Rossonero585\PhpExpression\Exceptions;


class IncorrectArgument extends \Exception
{
    /**
     * IncorrectArgument constructor.
     * @param string $argument
     */
    public function __construct(string $argument)
    {
        parent::__construct("$argument is not correct");
    }
}