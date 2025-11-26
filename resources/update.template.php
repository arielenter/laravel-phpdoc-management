<?php

use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;

$filePhpdocManager = new FilePhpdocManager(':file');

$phpdoc = [
    'file' => [
        ':file_summary',
        [['@author', ':name <:email>'],[ '@link', 'https://:web']]
    ],
    'class' => ':class_summary',
    'properties' => [
        '$:property_one_name' => [[ '@var', 'string', '$:property_one_name', ':property_one_desc' ]],
        '$:property_two_name' => [ ':property_two_desc', [ '@var', 'array' ]]
    ],
    'constants' => [':constant_name' => [':constat_desc', ['@var', 'string']]],
    'methods' => [
        ':method_one_name' => [
            ':method_one_summary', :method_one_long_desc,
            [
                [ '@param', 'array', '$:argument_one_name', ':argument_one_desc' ],
                [
                    '@param', 'string|array', '$:argument_two_name',
                    :argument_two_long_desc
                ]
            ],
            [ '@return', 'string', ':return_val_desc' ]
        ],
        ':method_two_name' => [
            ':method_two_summary', ['@param', 'int', '$:argument_three_name', ':argument_three_desc'],
            [ '@return', 'void' ]
        ]
    ]
];

$filePhpdocManager->update($phpdoc);
