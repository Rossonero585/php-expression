### Description

This library allows creating math expression using numbers, brackets, mathematical signs (+,-,*,/) and then execute them
 with different arguments. It also allows adding custom functions to be executed inside expression. 
 It works trough `eval` function that receives dynamically generated string and then run it.

### Example usage

#### Simple expression
```php
<?php

$expressionBuilder = new \Rossonero585\PhpExpression\ExpressionBuilder();

$expression = $expressionBuilder
    ->addArguments(['a', 'b'])
    ->create('a + b');

echo $expression->execute(["a" => 5, "b" => 5]);
// 10

echo $expression->execute(["a" => 2, "b" => 1]);
// 3

```

#### Add custom function
```php
<?php

$expressionBuilder = new \Rossonero585\PhpExpression\ExpressionBuilder();

$expression = $expressionBuilder
    ->addFunction('convert', function ($value, $curr1, $curr2) {
        // do some stuff here
        return 61;
    })
    ->addArguments(["x", "curr1", "curr2"])
    ->create('1.1 * convert(x, curr1, curr2) + 100');

$result = $expression->execute([
    "x" => 100,
    "curr1" => "USD",
    "curr2" => "RUB"
]);

```
