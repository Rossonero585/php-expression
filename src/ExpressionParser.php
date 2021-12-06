<?php

namespace Rossonero585\PhpExpression;

use Rossonero585\PhpExpression\Exceptions\IncorrectArgumentName;
use Rossonero585\PhpExpression\Exceptions\IncorrectBracket;
use Rossonero585\PhpExpression\Exceptions\IncorrectExpression;
use Rossonero585\PhpExpression\Exceptions\IncorrectToken;
use Rossonero585\PhpExpression\Exceptions\ParsingException;

class ExpressionParser
{
    const signs = ["+", "-", "*", "/", ","];

    /**
     * @var string
     */
    private $expression;

    /**
     * @var string[]
     */
    private $functions;

    /**
     * @var string[]
     */
    private $arguments;

    /**
     * @var Token
     */
    private $firstToken;

    /**
     * ExpressionParser constructor.
     *
     * @param string $expression
     * @param string[] $functions
     * @param string[] $arguments
     * @throws ParsingException
     */
    public function __construct(string $expression, array $functions, array $arguments)
    {
        $this->expression = trim($expression);
        $this->functions  = $functions;
        $this->arguments  = $arguments;

        $this->validate();
        $this->parse();
    }

    /**
     * @throws ParsingException
     */
    private function parse() : void
    {
        $exp = $this->expression;
        $tokens = [];
        $token = "";

        $brackets = ["(", ")"];

        $signs = array_merge(self::signs, $brackets);

        for ($i = 0; $i < strlen($exp); $i++) {

            if (" " === $exp[$i]) continue;

            if (!in_array($exp[$i], $signs)) {
                $token .= $exp[$i];
            }
            else {
                if ("" !== $token) $tokens[] = $this->createToken($token);
                $token = "";
                $tokens[] = $this->createToken($exp[$i]);
            }
        }

        if ("" !== $token) $tokens[] = $this->createToken($token);

        $this->validateTokens($tokens);

        for ($i = 0; $i < count($tokens) - 1; $i++) {
            $tokens[$i]->setNextToken($tokens[$i + 1]);
        }

        $this->firstToken = $tokens[0];
    }

    /**
     * @param string $content
     * @return Token
     * @throws IncorrectToken
     */
    private function createToken(string $content) : Token
    {
        if (in_array($content, $this->functions)) {
            return new Token($content, Token::TYPE_FUNCTION);
        }
        else if (in_array($content, $this->arguments)) {
            return new Token($content, Token::TYPE_ARGUMENT);
        }
        else if (in_array($content, self::signs)) {
            return new Token($content, Token::TYPE_SIGN);
        }
        else if (in_array($content, [")", "("])) {
            return new Token($content, Token::TYPE_BRACKET);
        }
        else if (preg_match("/^\d+(?:\.\d+)?$/", $content)) {
            return new Token($content, Token::TYPE_NUMBER);
        }
        else {
            throw new IncorrectToken($content);
        }
    }

    /**
     * @throws ParsingException
     */
    private function validate() : void
    {
        if (!preg_match("/^[a-zA-z\d\+\-\*\/\(\),\s\.\@]*[a-zA-z\d\)]$/", $this->expression)) {
            throw new IncorrectExpression($this->expression);
        }

        $uniqueArguments = [];

        foreach ($this->arguments as $argument) {
            if (!in_array($argument, $uniqueArguments)) {
                array_push($uniqueArguments, $argument);
            }
            else {
                throw new IncorrectArgumentName($argument);
            }
        }

        foreach ($this->arguments as $argument) {
            if (in_array($argument, $this->functions)) {
                throw new IncorrectArgumentName($argument);
            }
        }
    }

    /**
     * @param Token[] $tokens
     * @throws ParsingException
     */
    private function validateTokens(array $tokens) : void
    {
        /** @var Token $previousToken */
        $previousToken = null;
        $bracketCounter = 0;

        for ($i = 0; $i < count($tokens); $i++) {
            $currentToken = $tokens[$i];
            $content = $currentToken->getContent();
            if ("(" == $content) $bracketCounter++;
            if (")" == $content) $bracketCounter--;
            if ($bracketCounter < 0) throw new IncorrectBracket($this->expression);
            if (null !== $previousToken) {
                if ($previousToken->isSign() && $currentToken->isSign()) {
                    throw new IncorrectExpression($this->expression);
                }
                if ($previousToken->isFunction() && "(" !== $content) {
                    throw new IncorrectExpression($this->expression);
                }
                if ($previousToken->isArgument() && "(" == $content) {
                    throw new IncorrectExpression($this->expression);
                }
            }
            $previousToken = $currentToken;
        }

        if (0 !== $bracketCounter) throw new IncorrectBracket($this->expression);
    }

    /**
     * @return Token
     */
    public function getFirstToken() : Token
    {
        return $this->firstToken;
    }

}