<?php

$className = 'ExampleClass';
$propertyOne = 'one';
$propertyTwo = 'two';
$methodOne = 'methodOne';
$argumentThree = 'number';
$file = "tests/$className.php";

return [
    'content_placeholders' => [
        'class_name' => $className,
        'property_one_name' => $propertyOne,
        'property_two_name' => $propertyTwo,
        'constant_name' => 'MY_CONSTANT',
        'method_one_name' => 'methodOne',
        'argument_one_name' => 'argumentOne',
        'argument_two_name' => 'argTwo',
        'method_two_name' => 'methodTwo',
        'argument_three_name' => $argumentThree
    ],

    'update_values' => [
        'file' => $file,
        'file_summary' => 'Summary for the file.', 'name' => 'John Doe',
        'email' => 'john@doe.com', 'web' => 'doe.com',
        'class_summary' => 'Summary for the class.',
        'property_one_desc' => 'Property $' . $propertyOne . ' description.',
        'property_two_desc' => 'Property $' . $propertyTwo . ' description',
        'constat_desc' => 'Constant desc.',
        'method_one_summary' => "$methodOne summary.",
        'method_one_long_desc' => "'Very ' . str_repeat('long ', 20) "
            . ". 'desc.'", 'argument_one_desc' => 'Argument one desc.',
        'argument_two_long_desc' => "'Very ' . str_repeat('long ', 10) "
            . ". 'desc.'",
        'return_val_desc' => 'Return value desc.',
        'method_two_summary' => "Summary.",
        'argument_three_desc' => "Argument ‘{$argumentThree}’ desc."
    ],

    'array_values' => [
        'original' => 'original',
        'property_one' => 'property_one',
        'sumary' => 'Sumary',
        'propery_two' => 'propery_two',
        'my_constant' => 'my_constant',
        'method_one' => 'method_one',
        'method_two' => 'method_two',
        'file' => $file,
        'new' => 'new'
    ],
];
