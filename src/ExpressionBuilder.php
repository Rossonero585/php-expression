<?php

namespace Rossonero585\PhpExpression;

class ExpressionBuilder
{
    /**
     * @var callable[]
     */
    private $functions = [];

    /**
     * @var string[]
     */
    private $argumentNames = [];

    /**
     * @param string $name
     * @param callable $function
     * @return $this
     */
    public function addFunction(string $name, callable $function) : self
    {
        $this->functions[$name] = $function;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function addArgument(string $name) : self
    {
        $this->argumentNames[] = $name;
        return $this;
    }

    /**
     * @param string[] $arguments
     * @return $this
     */
    public function addArguments(array $arguments) : self
    {
        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function reset() : self
    {
        $this->argumentNames = [];
        $this->functions     = [];
        return $this;
    }

    /**
     * @param string $expression
     * @return Expression
     * @throws Exceptions\ParsingException
     */
    public function create(string $expression) : Expression
    {
        $expressionParser = new ExpressionParser($expression, array_keys($this->functions), $this->argumentNames);

        return new Expression($expressionParser->getFirstToken(), $this->functions, $this->argumentNames);
    }
}