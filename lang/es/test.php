<?php

$className = 'ClaseEjemplo';
$propertyTwo = 'dos';
$file = "tests/$className.php";

return [
    'content_placeholders' => [
        'class_name' => $className,
        'property_one_name' => 'uno',
        'property_two_name' => $propertyTwo,
        'constant_name' => 'MI_CONSTANTE',
        'method_one_name' => 'metodoUno',
        'argument_one_name' => 'argumentoUno',
        'argument_two_name' => 'argDos',
        'method_two_name' => 'metodoDos',
        'argument_three_name' => 'number'
    ],

    'update_values' => [
        'file' => $file,
        'file_summary' => 'Descripción resumida para el archivo.',
        'name' => 'Juan Perez',
        'email' => 'juan@prz.com',
        'web' => 'prz.com',
        'class_summary' => 'Descripción resumida de la clase.',
        'property_one_desc' => 'Descripción de la propiedad',
        'property_two_desc' => 'Descripción de la propiedad $' . $propertyTwo,
        'constat_desc' => 'Mi constante.',
        'method_one_summary' => "Resumen corto",
        'method_one_long_desc' => "'Descripción ' . str_repeat('muy ', 20) "
            . ". 'larga.'", 'argument_one_desc' => 'Descripción corta.',
        'argument_two_long_desc' => "'Descripción ' . str_repeat('muy ', 10) "
            . ". 'larga.'",
        'return_val_desc' => 'Descripción del valor de retorno.',
        'method_two_summary' => "Resumen corto.",
        'argument_three_desc' => 'Desc. corta.'
    ],

    'array_values' => [
        'original' => 'original',
        'property_one' => 'propiedad_uno',
        'sumary' => 'Desc',
        'propery_two' => 'propiedad_dos',
        'my_constant' => 'my_constant',
        'method_one' => 'metodo_uno',
        'method_two' => 'metodo_dos',
        'file' => $file,
        'new' => 'nuevo'
    ],
];
