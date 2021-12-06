<?php

namespace Rossonero585\PhpExpression;

class Token
{
    const TYPE_SIGN = 'sign';
    const TYPE_ARGUMENT = 'argument';
    const TYPE_FUNCTION = 'function';
    const TYPE_BRACKET = 'bracket';
    const TYPE_NUMBER = 'number';

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $type;

    /**
     * @var Token|null
     */
    private $nextToken;

    /**
     * Token constructor.
     * @param string $content
     * @param string $type
     */
    public function __construct(string $content, string $type) {
        $this->content = $content;
        $this->type = $type;
    }

    /**
     * @return Token|null
     */
    public function next() : ?Token
    {
        return $this->nextToken;
    }

    /**
     * @param Token $token
     */
    public function setNextToken(Token $token) : void
    {
        $this->nextToken = $token;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isFunction() : bool
    {
        return $this->type === self::TYPE_FUNCTION;
    }

    /**
     * @return bool
     */
    public function isArgument() : bool
    {
        return $this->type === self::TYPE_ARGUMENT;
    }

    /**
     * @return bool
     */
    public function isSign(): bool
    {
        return $this->type === self::TYPE_SIGN;
    }
}