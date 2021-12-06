<?php

namespace Rossonero585\PhpExpression\Tests;

use Rossonero585\PhpExpression\Exceptions\IncorrectArgumentName;
use Rossonero585\PhpExpression\Exceptions\IncorrectBracket;
use Rossonero585\PhpExpression\Exceptions\IncorrectExpression;
use Rossonero585\PhpExpression\Exceptions\IncorrectToken;
use Rossonero585\PhpExpression\ExpressionParser;
use PHPUnit\Framework\TestCase;
use Rossonero585\PhpExpression\Token;

class ExpressionParserTest extends TestCase
{
    public function testGetFirstToken()
    {
        $exp = "a+b+fun(fun(c,d))";

        $expressionParser = new ExpressionParser($exp, ["fun"], ["a", "b", "c", "d"]);

        /** @var Token[] $tokens */
        $tokens = [];

        $token = $expressionParser->getFirstToken();

        $outputString = "";

        do {
            $outputString .= $token->getContent();
            array_push($tokens, $token);
        }
        while ($token = $token->next());

        $this->assertEquals($exp, $outputString);


        $this->assertTrue($tokens[0]->isArgument());
        $this->assertTrue($tokens[2]->isArgument());
        $this->assertTrue($tokens[4]->isFunction());

        $this->assertCount(13, $tokens);
    }

    /**
     * @dataProvider incorrectExpressionsProvider
     */
    public function testIncorrectExpression(string $expression, array $functions, array $arguments)
    {
        $this->expectException(IncorrectExpression::class);

        new ExpressionParser($expression, $functions, $arguments);
    }

    /**
     * @dataProvider incorrectBracketProvider
     */
    public function testIncorrectBracket(string $expression, array $functions, array $arguments)
    {
        $this->expectException(IncorrectBracket::class);

        new ExpressionParser($expression, $functions, $arguments);
    }

    /**
     * @dataProvider incorrectTokenProvider
     */
    public function testIncorrectToken(string $expression, array $functions, array $arguments)
    {
        $this->expectException(IncorrectToken::class);

        new ExpressionParser($expression, $functions, $arguments);
    }

    /**
     * @dataProvider incorrectArgumentNameProvider
     */
    public function testIncorrectArgumentName(string $expression, array $functions, array $arguments)
    {
        $this->expectException(IncorrectArgumentName::class);

        new ExpressionParser($expression, $functions, $arguments);
    }


    /**
     * @dataProvider correctExpressionProvider
     */
    public function testCorrectExpression(string $expression, array $functions, array $arguments, string $expected)
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

    public function incorrectExpressionsProvider() : array
    {
        return [
            ["&a+b", [], ['a', 'b']],
            ["a++b", [], ['a', 'b']],
            [")a+b(", [], ['a', 'b']],
            ["", [], []]
        ];
    }

    public function incorrectBracketProvider() : array
    {
        return [
            ["(a+b))", [], ['a', 'b']],
            ["(a)+(b))", [], ['a', 'b']],
        ];
    }

    public function incorrectTokenProvider() : array
    {
        return [
            ["aa+b+1", [], ['a', 'b']],
            ["a+b+pow(a)", [], ['a', 'b']],
            ["2*aa", [], ['a', 'b']],
        ];
    }

    public function incorrectArgumentNameProvider() : array
    {
        return [
            ["a+b+a(2)", ["a"], ["a", "b"]],
            ["a+b", [], ["a", "a", "b"]]
        ];
    }

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
