<?php

namespace Rossonero585\PhpExpression\Exceptions;

class IncorrectExpression extends ParsingException
{
    /**
     * IncorrectExpression constructor.
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        parent::__construct("Expression $expression has incorrect symbols");
    }
}