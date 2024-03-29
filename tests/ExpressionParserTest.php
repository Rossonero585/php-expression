<?php

namespace Rossonero585\PhpExpression\Tests;

use Rossonero585\PhpExpression\Exceptions\IncorrectArgumentName;
use Rossonero585\PhpExpression\Exceptions\IncorrectBracket;
use Rossonero585\PhpExpression\Exceptions\IncorrectExpression;
use Rossonero585\PhpExpression\Exceptions\IncorrectToken;
use Rossonero585\PhpExpression\Exceptions\ParsingException;
use Rossonero585\PhpExpression\ExpressionParser;
use PHPUnit\Framework\TestCase;
use Rossonero585\PhpExpression\Token;

class ExpressionParserTest extends TestCase
{
    public function testGetFirstToken() : void
    {
        $exp = "a+b+fun(fun(c,d))";

        $expressionParser = new ExpressionParser($exp, ["fun"], ["a", "b", "c", "d"]);

        /** @var Token[] $tokens */
        $tokens = [];

        $token = $expressionParser->getFirstToken();

        $outputString = "";

        do {
            $outputString .= $token->getContent();
            $tokens[] = $token;
        }
        while ($token = $token->next());

        $this->assertEquals($exp, $outputString);


        $this->assertTrue($tokens[0]->isArgument());
        $this->assertTrue($tokens[2]->isArgument());
        $this->assertTrue($tokens[4]->isFunction());

        $this->assertCount(13, $tokens);
    }

    /**
     * @param string $expression
     * @param array<string> $functions
     * @param array<string> $arguments
     * @throws ParsingException
     * @dataProvider incorrectExpressionsProvider
     */
    public function testIncorrectExpression(string $expression, array $functions, array $arguments) : void
    {
        $this->expectException(IncorrectExpression::class);

        new ExpressionParser($expression, $functions, $arguments);
    }

    /**
     * @param string $expression
     * @param array<string> $functions
     * @param array<string> $arguments
     * @throws ParsingException
     * @dataProvider incorrectBracketProvider
     */
    public function testIncorrectBracket(string $expression, array $functions, array $arguments) : void
    {
        $this->expectException(IncorrectBracket::class);

        new ExpressionParser($expression, $functions, $arguments);
    }

    /**
     * @param string $expression
     * @param array<string> $functions
     * @param array<string> $arguments
     * @throws ParsingException
     * @dataProvider incorrectTokenProvider
     */
    public function testIncorrectToken(string $expression, array $functions, array $arguments) : void
    {
        $this->expectException(IncorrectToken::class);

        new ExpressionParser($expression, $functions, $arguments);
    }

    /**
     * @param string $expression
     * @param array<string> $functions
     * @param array<string> $arguments
     * @throws ParsingException
     * @dataProvider incorrectArgumentNameProvider
     */
    public function testIncorrectArgumentName(string $expression, array $functions, array $arguments) : void
    {
        $this->expectException(IncorrectArgumentName::class);

        new ExpressionParser($expression, $functions, $arguments);
    }


    /**
     * @param string $expression
     * @param array<string> $functions
     * @param array<string> $arguments
     * @param string $expected
     * @throws ParsingException
     * @dataProvider correctExpressionProvider
     */
    public function testCorrectExpression(string $expression, array $functions, array $arguments, string $expected) : void
    {
        $expressionParser = new ExpressionParser($expression, $functions, $arguments);

        $token = $expressionParser->getFirstToken();

        $outputString = "";

        do {
            $outputString .= $token->getContent();
        }
        while ($token = $token->next());


        $this->assertEquals($expected, $outputString);
    }

    /**
     * @return array<array{string, array<string>, array<string>}>
     */
    public function incorrectExpressionsProvider() : array
    {
        return [
            ["&a+b", [], ['a', 'b']],
            ["a++b", [], ['a', 'b']],
            [")a+b(", [], ['a', 'b']],
            ["", [], []]
        ];
    }

    /**
     * @return array<array{string, array<string>, array<string>}>
     */
    public function incorrectBracketProvider() : array
    {
        return [
            ["(a+b))", [], ['a', 'b']],
            ["(a)+(b))", [], ['a', 'b']],
        ];
    }

    /**
     * @return array<array{string, array<string>, array<string>}>
     */
    public function incorrectTokenProvider() : array
    {
        return [
            ["aa+b+1", [], ['a', 'b']],
            ["a+b+pow(a)", [], ['a', 'b']],
            ["2*aa", [], ['a', 'b']],
        ];
    }

    /**
     * @return array<array{string, array<string>, array<string>}>
     */
    public function incorrectArgumentNameProvider() : array
    {
        return [
            ["a+b+a(2)", ["a"], ["a", "b"]],
            ["a+b", [], ["a", "a", "b"]]
        ];
    }

    /**
     * @return array<array{string, array<string>, array<string>}>
     */
    public function correctExpressionProvider() : array
    {
        return [
            ["2 + 2 ", [], [], "2+2"],
            ["2.12222*a + b + c", [], ["a", "b", "c"], "2.12222*a+b+c"],
            [
                "1.33*@convert(value, fromcurr, tocurr, partnerID) + 200 + value",
                ["@convert"],
                ["value", "fromcurr", "tocurr", "partnerID"],
                "1.33*@convert(value,fromcurr,tocurr,partnerID)+200+value"
            ],
            ["1 + func(2.3*a)", ["func"], ["a"], "1+func(2.3*a)"]
        ];
    }
}
