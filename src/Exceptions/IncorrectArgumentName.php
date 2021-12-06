<?php

namespace Rossonero585\PhpExpression\Exceptions;

class IncorrectArgumentName extends ParsingException
{
    /**
     * IncorrectArgumentName constructor.
     * @param string $argument
     */
    public function __construct(string $argument)
    {
        parent::__construct($argument." has the same name with one of functions");
    }
}