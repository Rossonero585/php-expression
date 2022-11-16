<?php

namespace Rossonero585\PhpExpression\Tests;

use PHPUnit\Framework\TestCase;
use Rossonero585\PhpExpression\ExpressionBuilder;

class ExpressionTest extends TestCase
{
    public function testExecute() : void
    {
        $expressionBuilder = new ExpressionBuilder();

        $rates = [
            "USD" => 74,
            "GBP" => 95,
            "EUR" => 86
        ];

        $expression = $expressionBuilder
            ->addFunction('convert', function ($value, $currency, $partner) use ($rates) {
                $rate = $rates[$currency];
                if ($partner == 10) $rate = 0.9 * $rate;
                return $value / $rate;
            })
            ->addArguments(["cost", "currency", "partner"])
            ->create("1.11 * convert(cost, currency, partner) + 100");

        $this->assertEquals(
            round(1.11 * (100 / 74) + 100, 2),
            $expression->execute([
                "cost" => 100,
                "currency" => "USD",
                "partner" => 1
            ])
        );

        $this->assertEquals(
            round(1.11 * (100 / (86 * 0.9)) + 100, 2),
            $expression->execute([
                "cost" => 100,
                "currency" => "EUR",
                "partner" => 10
            ])
        );

        $this->assertEquals(
            round(1.11 * (100 / (95 * 0.9)) + 100, 2),
            $expression->execute([
                "cost" => 100,
                "currency" => "GBP",
                "partner" => 10
            ])
        );
    }
}
