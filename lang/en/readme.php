<?php

use Arielenter\LaravelPhpdocManagement\ServiceProvider;

$prefix = ServiceProvider::TRANSLATIONS . '::';

return [
    'class' => 'class',
    
    'method' => 'method',
    
    'usage_example' => 'Usage example',
    
    'suppose_we_have_a_file' => __(
        'Suppose we have a file ‘:file’ with the following content:',
        [ 'file' => __($prefix . 'test.update_values.file', locale: 'en') ]
    ),
    
    'we_can_run' => 'We can run de following code:',
    
    'its_new_content' => 'And now the file’s new content would be:',
    
    'suppose_we_run_code' => 'Suppose we run the following code:',
    
    'keys_have_been_changed' => __(
        'If we use ‘print_r’ on the variable ‘$:new’ we’ll now see that '
            . 'array’s key have been change accordingly:',
        [ 'new' => __($prefix . 'array_values.new', locale: 'en') ]
    ),

    'some_other_methods' => 'Some other methods',

    'array_to_phpdoc' => 'To create doc blocks form arrays, two instances of '
        . 'class ‘DocBlockCreator’ from the package arielenter/array-to-hpdoc '
        . 'are used. One creates doc blocks with an indentation for methods, '
        . 'properties and constants, and another without it for everything '
        . 'else.',

    'purpose' => 'With above mentioned in mind, the following methods are '
        . 'meant to be used to define their characteristics, like for '
        . 'instance, how long can a text from a doc block go before it is '
        . 'wrapped, and some other things.'
];
