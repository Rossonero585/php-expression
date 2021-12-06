<?php

namespace Rossonero585\PhpExpression\Tests;

use PHPUnit\Framework\TestCase;
use Rossonero585\PhpExpression\ExpressionFactory;

class ExpressionTest extends TestCase
{

    public function testExecute()
    {
        $expressionFactory = new ExpressionFactory();

        $rates = [
            "USD" => 74,
            "GBP" => 95,
            "EUR" => 86
        ];

        $expressionFactory->addFunction('convert', function ($value, $currency, $partner) use($rates) {
            $rate = $rates[$currency];
            if ($partner == 10) $rate = 0.9 * $rate;
            return $value / $rate;
        });

        $expression = $expressionFactory->createExpression(
            "1.11 * convert(cost, currency, partner) + 100",
            ["cost", "currency", "partner"]
        );

        $this->assertEquals(round(1.11 * (100 / 74) + 100, 2), $expression->execute([100, "USD", 1]));
        $this->assertEquals(round(1.11 * (100 / (86 * 0.9)) + 100, 2), $expression->execute([100, "EUR", 10]));
        $this->assertEquals(round(1.11 * (100 / (95 * 0.9)) + 100, 2), $expression->execute([100, "GBP", 10]));
    }

}
