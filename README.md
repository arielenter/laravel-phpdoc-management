[Español](README.es.md)

## class FilePhpdocManager

Manages the phpdoc comments of a given file.

### method __construct

```php
/** @param string $file Route of the file whose phpdoc wants to be manage. */
```

### method update

```php
/**
 * Inserts or replaces the phpdoc of the file.
 *
 * @param array $phpdoc Doc blocks that will be inserted or replaced. Keys
 *                      ‘methods’, ‘properties’ and ‘constants’
 *                      should hold the doc blocks for every corresponding
 *                      element. For instance, if methods ‘update’ and
 *                      ‘delete’ exist, the value of the ‘methods’ key
 *                      would be an array containing keys ‘update’ and
 *                      ‘delete’, while their values should be their
 *                      corresponding doc blocks. Key ‘file’ would contain
 *                      the file’s doc block. Every other key would be used
 *                      for any line that starts with such key, this could
 *                      include the words ‘class’, ‘interface’,
 *                      ‘trail’, ‘final’, ‘abstract’ or any other
 *                      word you might need. Lastly, every doc block should be
 *                      given as an array or a string in accordance to the
 *                      arielenter/array-to-phpdoc package.
 *
 * @return string The new content of the file.
 */
```

#### Usage example

Suppose we have a file ‘tests/ExampleClass.php’ with the following content:

```php
<?php

namespace Tests;

class ExampleClass
{
    public string $one;

    public array $two;

    public const string MY_CONSTANT = 'xyz';

    public function methodOne(array $argumentOne, string|array $argTwo): string
    {
        return 'I’m a string';
    }

    public function methodTwo(int $number): void
    {
        return;
    }
}
```

We can run de following code:

```php
<?php

use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;

$filePhpdocManager = new FilePhpdocManager('tests/ExampleClass.php');

$phpdoc = [
    'file' => [
        'Summary for the file.',
        [['@author', 'John Doe <john@doe.com>'],[ '@link', 'https://doe.com']]
    ],
    'class' => 'Summary for the class.',
    'properties' => [
        '$one' => [[ '@var', 'string', '$one', 'Property $one description.' ]],
        '$two' => [ 'Property $two description', [ '@var', 'array' ]]
    ],
    'constants' => ['MY_CONSTANT' => ['Constant desc.', ['@var', 'string']]],
    'methods' => [
        'methodOne' => [
            'methodOne summary.', 'Very ' . str_repeat('long ', 20) . 'desc.',
            [
                [ '@param', 'array', '$argumentOne', 'Argument one desc.' ],
                [
                    '@param', 'string|array', '$argTwo',
                    'Very ' . str_repeat('long ', 10) . 'desc.'
                ]
            ],
            [ '@return', 'string', 'Return value desc.' ]
        ],
        'methodTwo' => [
            'Summary.', ['@param', 'int', '$number', 'Argument ‘number’ desc.'],
            [ '@return', 'void' ]
        ]
    ]
];

$filePhpdocManager->update($phpdoc);

```

And now the file’s new content would be:

```php
<?php

/**
 * Summary for the file.
 *
 * @author John Doe <john@doe.com>
 * @link   https://doe.com
 */

namespace Tests;

/** Summary for the class. */
class ExampleClass
{
    /** @var string $one Property $one description. */
    public string $one;

    /**
     * Property $two description
     *
     * @var array
     */
    public array $two;

    /**
     * Constant desc.
     *
     * @var string
     */
    public const string MY_CONSTANT = 'xyz';

    /**
     * methodOne summary.
     *
     * Very long long long long long long long long long long long long long
     * long long long long long long long desc.
     *
     * @param array        $argumentOne Argument one desc.
     * @param string|array $argTwo      Very long long long long long long long
     *                                  long long long desc.
     *
     * @return string Return value desc.
     */
    public function methodOne(array $argumentOne, string|array $argTwo): string
    {
        return 'I’m a string';
    }

    /**
     * Summary.
     *
     * @param int $number Argument ‘number’ desc.
     *
     * @return void
     */
    public function methodTwo(int $number): void
    {
        return;
    }
}
```

[arielenter/array-to-phpdoc](https://github.com/arielenter/array-to-phpdoc)

### method translateKeys

```php
/**
 * Converts keys to their correct naming convention: camel for methods and
 * properties, and snake with uppercase for constants.
 *
 * A money sight is added to the begging of properties if they don’t have it
 * already. Methods and properties starting with an underscore are left
 * untouched. Properties starting with a money sight are also untouched.
 *
 * @param array $phpdoc Array holding the keys that will be converted.
 *
 * @return array The new array with the converted keys.
 */
```

#### Usage example

Suppose we run the following code:

```php
<?php

use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;

$original = [
	'properties' => [ 'property_one' => 'Sumary', 'propery_two' => 'Sumary' ],
    'constants' => [ 'my_constant' => 'Sumary' ],
	'methods' => [ 'method_one' => 'Sumary.', 'method_two' => 'Sumary.' ]
];

$filePhpdocManager = new FilePhpdocManager('tests/ExampleClass.php');
$new = $filePhpdocManager->translateKeys($original);

```

If we use ‘print_r’ on the variable ‘$arielenter_laravel_phpdoc_management::array_values.new’ we’ll now see that array’s key have been change accordingly:

```php
Array
(
    [properties] => Array
        (
            [$propertyOne] => Sumary
            [$properyTwo] => Sumary
        )

    [constants] => Array
        (
            [MY_CONSTANT] => Sumary
        )

    [methods] => Array
        (
            [methodOne] => Sumary.
            [methodTwo] => Sumary.
        )

)

```

## Some other methods

To create doc blocks form arrays, two instances of class ‘DocBlockCreator’ from the package arielenter/array-to-hpdoc are used. One creates doc blocks with an indentation for methods, properties and constants, and another without it for everything else.

With above mentioned in mind, the following methods are meant to be used to define their characteristics, like for instance, how long can a text from a doc block go before it is wrapped, and some other things.

### method setMaxLineLength

```php
/**
 * Sets the max line width for both the doc block creators
 * ‘$docBlockCreator’ and ‘$crtrWithIndent’ properties, which are used
 * to create doc bocks from arrays.
 *
 * @return self
 */
```

### method setMinLastColumnWidth

```php
/**
 * Sets the min last column width for both the doc block creators
 * ‘$docBlockCreator’ and ‘$crtrWithIndent’ properties, which are used
 * to create doc bocks from arrays.
 *
 * @return self
 */
```

### method setIndentWidth

```php
/**
 * Sets an indentation width for the doc block creator property
 * ‘$crtrWithIndent’ exclusively.
 *
 * @return self
 */
```

### method setUseTabForIndentation

```php
/**
 * Sets wether or not a tab is used instead of spaces for the doc block creator
 * property ‘$crtrWithIndent’ exclusively.
 *
 * @return self
 */
```

### method getDocBlockCreator

```php
/**
 * Gives access to the ‘$docBlockCreator’ property individually.
 *
 * @return DocBlockCreator
 */
```

### method getCrtrWithIndent

```php
/**
 * Gives access to the ‘$crtrWithIndent’ property individually.
 *
 * @return DocBlockCreator
 */
```
