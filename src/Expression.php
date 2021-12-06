<?php

namespace Rossonero585\PhpExpression;

use Rossonero585\PhpExpression\Exceptions\ExecutionException;
use Rossonero585\PhpExpression\Exceptions\IncorrectArgument;
use Rossonero585\PhpExpression\Exceptions\IncorrectArgumentCount;

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
     * @param array<int|float|string> $arguments
     * @param int $precision
     * @return float
     * @throws ExecutionException
     * @throws IncorrectArgument
     * @throws IncorrectArgumentCount
     */
    public function execute(array $arguments, int $precision = 2) : float
    {
        $countPassed = count($arguments);
        $countNeeded = count($this->argumentNames);

        if ($countNeeded !== $countPassed) {
            throw new IncorrectArgumentCount($countNeeded, $countPassed);
        }

        foreach ($arguments as $argument) {
            if (!preg_match("/^(\d+(?:\.\d+)?)|(\w+)$/", (string)$argument)) {
                throw new IncorrectArgument((string)$argument);
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
                $key = array_search($token->getContent(), $this->argumentNames);
                $evalPart = $arguments[$key];
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