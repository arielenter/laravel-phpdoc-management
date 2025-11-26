[English](README.md)

## clase FilePhpdocManager

Permite administrar los comentarios phpdoc de un archivo dado.

### método __construct

```php
/**
 * @param string $file Ruta al archivo cuya documentación se desea administrar.
 */
```

### método update

```php
/**
 * Inserta o remplaza la documentación existen en el archivo.
 *
 * @param array $phpdoc Bloques de documentación que serán insertados o
 *                      remplazados. Las llaves ‘methods’, ‘properties’
 *                      y ‘constants’ deberán contener los bloques de
 *                      documentación correspondientes a cada tipo de elemento
 *                      que corresponda. Por ejemplo, si se tiene los métodos
 *                      ‘update’ y ‘delete’, el valor de la llave
 *                      ‘methods’ deberá ser un arreglo que contenga dos
 *                      llaves ‘update’ y ‘delete’ cuyos valores a su
 *                      vez deberán ser sus bloques de documentación. La llave
 *                      ‘file’ se usa para el bloque doc del archivo.
 *                      Cualquier otro llave deberá ser usada para documentar
 *                      lineas que empiecen con dicha llave, tales como
 *                      ‘class’, ‘interface’, ‘trail’, ‘final’,
 *                      ‘abstract’ o cualquier otro que pudiera necesitarse.
 *                      Finalmente, cada bloque phpdoc deberá ser un arreglo o
 *                      cadena en concordancia con el paquete
 *                      arielenter/array-to-phpdoc
 *
 * @return string El nuevo contenido del archivo.
 */
```

#### Ejemplo de uso

Supongamos que tenemos un archivo ‘tests/ClaseEjemplo.php’ con el siguiente contenido:

```php
<?php

namespace Tests;

class ClaseEjemplo
{
    public string $uno;

    public array $dos;

    public const string MI_CONSTANTE = 'xyz';

    public function metodoUno(array $argumentoUno, string|array $argDos): string
    {
        return 'I’m a string';
    }

    public function metodoDos(int $number): void
    {
        return;
    }
}
```

Podemos ejecutar el siguiente código:

```php
<?php

use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;

$filePhpdocManager = new FilePhpdocManager('tests/ClaseEjemplo.php');

$phpdoc = [
    'file' => [
        'Descripción resumida para el archivo.',
        [['@author', 'Juan Perez <juan@prz.com>'],[ '@link', 'https://prz.com']]
    ],
    'class' => 'Descripción resumida de la clase.',
    'properties' => [
        '$uno' => [[ '@var', 'string', '$uno', 'Descripción de la propiedad' ]],
        '$dos' => [ 'Descripción de la propiedad $dos', [ '@var', 'array' ]]
    ],
    'constants' => ['MI_CONSTANTE' => ['Mi constante.', ['@var', 'string']]],
    'methods' => [
        'metodoUno' => [
            'Resumen corto', 'Descripción ' . str_repeat('muy ', 20) . 'larga.',
            [
                [ '@param', 'array', '$argumentoUno', 'Descripción corta.' ],
                [
                    '@param', 'string|array', '$argDos',
                    'Descripción ' . str_repeat('muy ', 10) . 'larga.'
                ]
            ],
            [ '@return', 'string', 'Descripción del valor de retorno.' ]
        ],
        'metodoDos' => [
            'Resumen corto.', ['@param', 'int', '$number', 'Desc. corta.'],
            [ '@return', 'void' ]
        ]
    ]
];

$filePhpdocManager->update($phpdoc);

```

Y ahora el archivo tendrá el siguiente contenido nuevo:

```php
<?php

/**
 * Descripción resumida para el archivo.
 *
 * @author Juan Perez <juan@prz.com>
 * @link   https://prz.com
 */

namespace Tests;

/** Descripción resumida de la clase. */
class ClaseEjemplo
{
    /** @var string $uno Descripción de la propiedad */
    public string $uno;

    /**
     * Descripción de la propiedad $dos
     *
     * @var array
     */
    public array $dos;

    /**
     * Mi constante.
     *
     * @var string
     */
    public const string MI_CONSTANTE = 'xyz';

    /**
     * Resumen corto
     *
     * Descripción muy muy muy muy muy muy muy muy muy muy muy muy muy muy muy
     * muy muy muy muy muy larga.
     *
     * @param array        $argumentoUno Descripción corta.
     * @param string|array $argDos       Descripción muy muy muy muy muy muy
     *                                   muy muy muy muy larga.
     *
     * @return string Descripción del valor de retorno.
     */
    public function metodoUno(array $argumentoUno, string|array $argDos): string
    {
        return 'I’m a string';
    }

    /**
     * Resumen corto.
     *
     * @param int $number Desc. corta.
     *
     * @return void
     */
    public function metodoDos(int $number): void
    {
        return;
    }
}
```

[arielenter/array-to-phpdoc](https://github.com/arielenter/array-to-phpdoc)

### método translateKeys

```php
/**
 * Convierte las llaves del arreglo utilizando el estilo de nombramiento
 * correspondiente: camello para métodos y propiedades, y serpiente con
 * mayúsculas para constantes.
 *
 * El símbolo de dinero es agregado al principio de propiedades en caso de que
 * no lo tengan de antemano. Los métodos y propiedades que empiecen con un
 * guion bajo se dejan tal cual, y lo mismo ocurre para propiedades que inicien
 * con el símbolo de dinero de antemano.
 *
 * @param array $phpdoc El arreglo que contiene las llaves que serán
 *                      convertidas.
 *
 * @return array Arreglo con las llaves convertidas.
 */
```

#### Ejemplo de uso

Supongamos que corremos el siguiente código:

```php
<?php

use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;

$original = [
	'properties' => [ 'propiedad_uno' => 'Desc', 'propiedad_dos' => 'Desc' ],
    'constants' => [ 'my_constant' => 'Desc' ],
	'methods' => [ 'metodo_uno' => 'Desc.', 'metodo_dos' => 'Desc.' ]
];

$filePhpdocManager = new FilePhpdocManager('tests/ClaseEjemplo.php');
$nuevo = $filePhpdocManager->translateKeys($original);

```

Si usamos la función ‘print_r’ en la variable ‘$nuevo’ veremos que las llaves han sido renombradas de manera correspondiente:

```php
Array
(
    [properties] => Array
        (
            [$propiedadUno] => Desc
            [$propiedadDos] => Desc
        )

    [constants] => Array
        (
            [MY_CONSTANT] => Desc
        )

    [methods] => Array
        (
            [metodoUno] => Desc.
            [metodoDos] => Desc.
        )

)

```

## Algunos otros métodos

Para crear bloques de documentación a partir de arreglos, se utilizan dos instancias de la clase ‘DocBlockCreator’ del paquete ‘arielenter/array-to-phpdoc’. Uno crea bloques ‘doc’ con sangría izquierda para métodos, propiedades y constantes, y otro sin sangría para todos los demás. 

Teniendo en cuenta lo anterior, los siguientes métodos pueden ser utilizados para establecer sus características, como por ejemplo que tan largo pueden ser el texto de un bloque de documentación antes de que sea ajustado en más de una linea.

### método setMaxLineLength

```php
/**
 * Establece un ancho máximo por linea de texto a las propiedades
 * ‘$docBlockCreator’ y ‘$crtrWithIndent’ las cuales son utilizadas para
 * crear bloques de documentación a partir de arreglos.
 *
 * @return self
 */
```

### método setMinLastColumnWidth

```php
/**
 * Establece un ancho mínimo para las ultimas columnas a las propiedades
 * ‘$docBlockCreator’ y ‘$crtrWithIndent’ las cuales son utilizadas para
 * crear bloques de documentación a partir de arreglos.
 *
 * @return self
 */
```

### método setIndentWidth

```php
/**
 * Establece un ancho de la sangría izquierda para el creador de bloques de
 * documentación almacenado en la propiedad ‘$crtrWithIndent’.
 *
 * @return self
 */
```

### método setUseTabForIndentation

```php
/**
 * Establece si se utilizara un tabulador en lugar de espacios para la sangría
 * izquierda para el creador de bloques de documentación almacenado en la
 * propiedad ‘$crtrWithIndent’.
 *
 * @return self
 */
```

### método getDocBlockCreator

```php
/**
 * Proporciona acceso a la propiedad ‘$docBlockCreator’ de manera
 * individual.
 *
 * @return DocBlockCreator
 */
```

### método getCrtrWithIndent

```php
/**
 * Proporciona acceso a la propiedad ‘$crtrWithIndent’ de manera individual.
 *
 * @return DocBlockCreator
 */
```
