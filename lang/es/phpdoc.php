<?php

use Arielenter\LaravelPhpdocManagement\ServiceProvider;
use Illuminate\Support\Arr;

$a = Arr::dot(__(ServiceProvider::TRANSLATIONS . '::phpdoc', locale: 'en'));

$a['class'] = 'Permite administrar los comentarios phpdoc de un archivo '
    . 'dado.';

$a['properties.doc_block_pattern.var.desc'] = 'Patrón ‘regex’ utilizado para '
    . 'identificar bloques de documentación con el objetivo de que puedan ser '
    . 'remplazados por otros nuevos.';
$pattern = 'Patrón ‘regex’ que sera utilizado para buscar un';
$a['properties.methods.var.desc'] = "{$pattern} método dado.";
$a['properties.properties.var.desc'] = "{$pattern}a propiedad dada.";
$a['properties.constants.var.desc'] = "{$pattern}a constante dada.";

$a['methods.__construct.param.desc'] = 'Ruta al archivo cuya documentación se '
    . 'desea administrar.';
$a['methods.update.desc'] = 'Inserta o remplaza la documentación existen en '
    . 'el archivo.';
$phpdoc =  'Bloques de documentación que serán insertados o remplazados. Las '
    . 'llaves ‘methods’, ‘properties’ y ‘constants’ deberán contener los '
    . 'bloques de documentación correspondientes a cada tipo de elemento que '
    . 'corresponda. Por ejemplo, si se tiene los métodos ‘update’ y ‘delete’, '
    . 'el valor de la llave ‘methods’ deberá ser un arreglo que contenga dos '
    . 'llaves ‘update’ y ‘delete’ cuyos valores a su vez deberán ser sus '
    . 'bloques de documentación. La llave ‘file’ se usa para el bloque doc del '
    . 'archivo. Cualquier otro llave deberá ser usada para documentar lineas '
    . 'que empiecen con dicha llave, tales como ‘class’, ‘interface’, ‘trail’, '
    . '‘final’, ‘abstract’ o cualquier otro que pudiera necesitarse. '
    . 'Finalmente, cada bloque phpdoc deberá ser un arreglo o cadena en '
    . 'concordancia con el paquete arielenter/array-to-phpdoc';
$a['methods.update.param.desc'] = $phpdoc;
$a['methods.update.return.desc'] = 'El nuevo contenido del archivo.';
$a['methods.translate_keys.summary'] = 'Convierte las llaves del arreglo '
    . 'utilizando el estilo de nombramiento correspondiente: camello para '
    . 'métodos y propiedades, y serpiente con mayúsculas para constantes.';
$a['methods.translate_keys.desc'] = 'El símbolo de dinero es agregado al '
    . 'principio de propiedades en caso de que no lo tengan de antemano. Los '
    . 'métodos y propiedades que empiecen con un guion bajo se dejan tal cual, '
    . 'y lo mismo ocurre para propiedades que inicien con el símbolo de dinero '
    . 'de antemano.';
$a['methods.translate_keys.param.desc'] = 'El arreglo que contiene las llaves '
    . 'que serán convertidas.';
$a['methods.translate_keys.return.desc'] = 'Arreglo con las llaves '
    . 'convertidas.';
$des = 'Proporciona acceso a la propiedad ‘$:p’ de manera individual.';
$a['methods.get_doc_block_creator.desc'] = __($des, ['p' => 'docBlockCreator']);
$a['methods.get_crtr_with_indent.desc'] = __($des, ['p' => 'crtrWithIndent']);
$est = 'Establece un :x a las propiedades ‘$docBlockCreator’ y ‘$crtrWithIndent’ '
    . 'las cuales son utilizadas para crear bloques de documentación a partir '
    . 'de arreglos.';
$x = 'ancho máximo por linea de texto';
$a['methods.set_max_line_length.desc'] = __($est, compact('x'));
$x = 'ancho mínimo para las ultimas columnas';
$a['methods.set_min_last_column_width.desc'] = __($est, compact('x'));
$establece = 'Establece :w para el creador de bloques de documentación '
    . 'almacenado en la propiedad ‘$crtrWithIndent’.';
$w = 'un ancho de la sangría izquierda';
$a['methods.set_indent_width.desc'] = __($establece, compact('w'));
$w = 'si se utilizara un tabulador en lugar de espacios para la sangría '
    . 'izquierda';
$a['methods.set_use_tab_for_indentation.desc'] = __($establece, compact('w'));

return Arr::undot($a);
