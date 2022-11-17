<?php

namespace Rossonero585\PhpExpression;

use Rossonero585\PhpExpression\Exceptions\ArgumentIsNotPassed;
use Rossonero585\PhpExpression\Exceptions\ExecutionException;
use Rossonero585\PhpExpression\Exceptions\IncorrectArgument;

class Expression
{
    /**
     * @var Token
     */
    private $token;

    /**
     * @var callable[]
     */
    private $functions;

    /**
     * @var string[]
     */
    private $argumentNames;

    /**
     * Expression constructor.
     * @param Token $token
     * @param callable[] $functions
     * @param string[] $argumentNames
     */
    public function __construct(Token $token, array $functions, array $argumentNames)
    {
        $this->token = $token;
        $this->functions = $functions;
        $this->argumentNames = $argumentNames;
    }

    /**
     * @param array<string, float|string> $arguments
     * @param int $precision
     * @return float
     * @throws ExecutionException
     */
    public function execute(array $arguments, int $precision = 2) : float
    {
        $passedArguments = array_keys($arguments);

        foreach ($this->argumentNames as $argumentName) {
            if (!in_array($argumentName, $passedArguments)) {
                throw new ArgumentIsNotPassed($argumentName);
            }
        }

        foreach ($arguments as $name => $argument) {
            if (!preg_match("/^(\d+(?:\.\d+)?)|(\w+)$/", (string)$argument)) {
                throw new IncorrectArgument($name, (string)$argument);
            }
        }

        $eval = $this->prepareString($arguments);

        $result = null;

        try{
            eval('$result='.$eval.';');

        }catch(\ParseError $p){
            throw new ExecutionException($p->getMessage());
        }

        return round($result, $precision);
    }

    /**
     * @param mixed[] $arguments
     * @return string
     */
    private function prepareString(array $arguments) : string
    {
        $token = $this->token;

        $evalString = "";

        do {
            if ($token->isFunction()) {
                $evalPart = '$this->functions[\''.$token->getContent().'\']';
            } elseif ($token->isArgument()) {
                $evalPart = $arguments[$token->getContent()];
                if (is_string($evalPart)) $evalPart = '\''.$evalPart.'\'';
            } else {
                $evalPart = $token->getContent();
            }
            $evalString .= $evalPart;
        }
        while ($token = $token->next());

        return $evalString;
    }
}