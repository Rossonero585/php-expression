<?php

namespace Rossonero585\PhpExpression;

use Rossonero585\PhpExpression\Exceptions\ParsingException;

class ExpressionFactory
{
    /**
     * @var callable[]
     */
    private $functions = [];

    /**
     * @param string $expression
     * @param string[] $argumentNames
     * @throws ParsingException
     * @return Expression
     */
    public function createExpression(string $expression, array $argumentNames) : Expression
    {
        $expressionParser = new ExpressionParser($expression, array_keys($this->functions), $argumentNames);

        return new Expression($expressionParser->getFirstToken(), $this->functions, $argumentNames);
    }

    /**
     * @param string $key
     * @param callable $func
     */
    public function addFunction(string $key, callable $func) : void
    {
        $this->functions[$key] = $func;
    }

    /**
     * @param string $expression
     * @param string[] $argumentNames
     * @return bool
     */
    public function validateExpression(string $expression, array $argumentNames) : bool
    {
        try {
            new ExpressionParser($expression, array_keys($this->functions), $argumentNames);
            return true;
        }
        catch (ParsingException $exception) {
            return false;
        }
    }

}