<?php

namespace Rossonero585\PhpExpression\Tests;

use Rossonero585\PhpExpression\ExpressionFactory;
use PHPUnit\Framework\TestCase;

class ExpressionFactoryTest extends TestCase
{

    /**
     * @dataProvider expressionProvider
     */
    public function testValidateExpression(string $expression, array $args, bool $result)
    {
        $expressionFactory = new ExpressionFactory();

        $this->assertEquals(
            $result,
            $expressionFactory->validateExpression($expression, $args)
        );
    }

    public function expressionProvider()
    {
        return [
            ["a+b", ["a", "b"], true],
            ["a+b+a()", ["a", "b"], false],
            ["\dfds....", [], false],
        ];
    }
}
