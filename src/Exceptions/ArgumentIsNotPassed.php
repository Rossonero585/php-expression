<?php

namespace Rossonero585\PhpExpression\Exceptions;

class ArgumentIsNotPassed extends ExecutionException
{
    public function __construct(string $argumentName)
    {
        parent::__construct(
            sprintf("Argument '%s' is not passed", $argumentName)
        );
    }
}