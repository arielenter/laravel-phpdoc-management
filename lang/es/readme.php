<?php

use Arielenter\LaravelPhpdocManagement\ServiceProvider;

$prefix = ServiceProvider::TRANSLATIONS . '::';

return [
    'class' => 'clase',
    
    'method' => 'método',
    
    'usage_example' => 'Ejemplo de uso',
    
    'suppose_we_have_a_file' => __(
        'Supongamos que tenemos un archivo ‘:file’ con el siguiente contenido:',
        [ 'file' => __($prefix . 'test.update_values.file', locale: 'es') ]
    ),
    
    'we_can_run' => 'Podemos ejecutar el siguiente código:',
    
    'its_new_content' => 'Y ahora el archivo tendrá el siguiente contenido '
        . 'nuevo:',
    
    'suppose_we_run_code' => 'Supongamos que corremos el siguiente código:',
    
    'keys_have_been_changed' => __(
        'Si usamos la función ‘print_r’ en la variable ‘$:new’ veremos que las '
            . 'llaves han sido renombradas de manera correspondiente:',
        [ 'new' => __($prefix . 'array_values.new', locale: 'es') ]
    ),

    'some_other_methods' => 'Algunos otros métodos',

    'array_to_phpdoc' => 'Para crear bloques de documentación a partir de '
        . 'arreglos, se utilizan dos instancias de la clase ‘DocBlockCreator’ '
        . 'del paquete ‘arielenter/array-to-phpdoc’. Uno crea bloques ‘doc’ '
        . 'con sangría izquierda para métodos, propiedades y constantes, y '
        . 'otro sin sangría para todos los demás. ',

    'purpose' => 'Teniendo en cuenta lo anterior, los siguientes métodos '
        . 'pueden ser utilizados para establecer sus características, como por '
        . 'ejemplo que tan largo pueden ser el texto de un bloque de '
        . 'documentación antes de que sea ajustado en más de una linea.'
];
