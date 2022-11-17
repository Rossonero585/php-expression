<?php

namespace Rossonero585\PhpExpression\Exceptions;


class IncorrectArgument extends ExecutionException
{
    /**
     * IncorrectArgument constructor.
     * @param string $argumentName
     * @param string $argument
     */
    public function __construct(string $argumentName, string $argument)
    {
        parent::__construct(sprintf("Value '%s' of argument '%s' is not correct", $argumentName, $argument));
    }
}