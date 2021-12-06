<?php

namespace Rossonero585\PhpExpression\Exceptions;

class IncorrectBracket extends ParsingException
{
    /**
     * IncorrectBracket constructor.
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        parent::__construct($expression." has not closed bracket");
    }
}