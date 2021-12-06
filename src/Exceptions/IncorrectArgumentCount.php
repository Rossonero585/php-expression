<?php

namespace Rossonero585\PhpExpression\Exceptions;


class IncorrectArgumentCount extends \Exception
{
    /**
     * IncorrectArgumentCount constructor.
     * @param int $needCount
     * @param int $passedCount
     */
    public function __construct(int $needCount, int $passedCount)
    {
        parent::__construct("Expression requires $needCount arguments, $passedCount passed");
    }
}