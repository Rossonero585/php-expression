<?php

namespace Rossonero585\PhpExpression\Exceptions;

class IncorrectToken extends ParsingException
{
    /**
     * IncorrectToken constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        parent::__construct("Unknown $token");
    }
}