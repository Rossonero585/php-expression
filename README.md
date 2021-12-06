### Description

This library allows creating math expression using numbers, brackets, mathematical signs (+,-,*,/) and then execute them
 with different arguments. It also allows adding custom functions to be executed inside expression. 
 It works trough `eval` function that receives dynamically generated string and then run it.

### Example usage

#### Simple expression
```php
<?php

use Rossonero585\PhpExpression\ExpressionFactory;

$expressionFactory = new ExpressionFactory();

$expression = $expressionFactory->createExpression('a + b', ['a', 'b']);

echo $expression->execute([5,5]);
// 10

echo $expression->execute([2,1]);
// 3

```

#### Add custom function
```php
<?php

use Rossonero585\PhpExpression\ExpressionFactory;

$expressionFactory = new ExpressionFactory();

$expressionFactory->addFunction('convert', function ($value, $curr1, $curr2) {
    // .. do some stuff here
});

$expression = $expressionFactory->createExpression('1.1 * convert(x, curr1, curr2) + 100', ['x', 'curr1', 'curr2']);

$result = $expression->execute([100, 'RUR', 'USD']);

```
