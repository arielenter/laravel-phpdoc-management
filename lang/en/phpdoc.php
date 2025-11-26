<?php

$pattern = 'Regex pattern that will be used to look for given';
$phpdoc =  'Doc blocks that will be inserted or replaced. Keys ‘methods’, '
    . '‘properties’ and ‘constants’ should hold the doc blocks for every '
    . 'corresponding element. For instance, if methods ‘update’ and ‘delete’ '
    . 'exist, the value of the ‘methods’ key would be an array containing keys '
    . '‘update’ and ‘delete’, while their values should be their corresponding '
    . 'doc blocks. Key ‘file’ would contain the file’s doc block. Every other '
    . 'key would be used for any line that starts with such key, this could '
    . 'include the words ‘class’, ‘interface’, ‘trail’, ‘final’, ‘abstract’ or '
    . 'any other word you might need. Lastly, every doc block should be given '
    . 'as an array or a string in accordance to the arielenter/array-to-phpdoc '
    . 'package.';
$gives = 'Gives access to the ‘$:property’ property individually.';
$sets = 'Sets the :what for both the doc block creators ‘$docBlockCreator’ and '
    . '‘$crtrWithIndent’ properties, which are used to create doc bocks from '
    . 'arrays.';
$alsoSets = 'Sets :what for the doc block creator property ‘$crtrWithIndent’ '
    . 'exclusively.';

return [
    'file' => [
        'Part of the arielenter/laravel-phpdoc-management package.',
        'PHP version 8+',
        [
            [ '@category', 'Phpdoc' ],
            [ '@package', 'Arielenter\Laravel\Phpdoc\Management' ],
            [ '@author', 'Ariel Del Valle Lozano <arielmazatlan@gmail.com>' ],
            [ '@copyright', '2025 Ariel Del Valle Lozano' ],
            [
                '@license', 'http://www.gnu.org/licenses/gpl-3.0.html GNU '
                    . 'General Public License (GPL) version 3'
            ],
            [
                '@link',
                'https://github.com/arielenter/laravel-phpdoc-management'
            ],
        ]
    ],
    
    'class' => 'Manages the phpdoc comments of a given file.',
    
    'properties' => [
        'doc_block_pattern' => [
            'var' => [
                '@var', 'string', '$docBlockPattern',
                'desc' => 'Pattern used to identified doc blocks in order to '
                    . 'be replaced by new ones.'
            ]
        ],
        'methods' => [
            'var' => [
                '@var', 'string', '$methods', 'desc' => "{$pattern} method."
            ]
        ],
        'properties' => [
            'var' => [
                '@var', 'string', '$methods', 'desc' => "{$pattern} property."
            ]
        ],
        'constants' => [
            'var' => [
                '@var', 'string', '$methods', 'desc' => "{$pattern} constant."
            ]
        ]
    ],
    
    'methods' => [
        '__construct' => [
            'param' => [
                '@param', 'string', '$file',
                'desc' => 'Route of the file whose phpdoc wants to be manage.'
            ]
        ],
        
        'update' => [
            'desc' => 'Inserts or replaces the phpdoc of the file.',
            'param' => [ '@param', 'array', '$phpdoc', 'desc' => $phpdoc ],
            'return' => [
                '@return', 'string', 'desc' => 'The new content of the file.'
            ]
        ],
        
        'translate_keys' => [
            'summary' => 'Converts keys to their correct naming convention: '
                . 'camel for methods and properties, and snake with uppercase '
                . 'for constants.',
            'desc' => 'A money sight is added to the begging of properties if '
                . 'they don’t have it already. Methods and properties starting '
                . 'with an underscore are left untouched. Properties starting '
                . 'with a money sight are also untouched.',
            'param' => [
                '@param', 'array', '$phpdoc',
                'desc' =>'Array holding the keys that will be converted.'
            ],
            'return' => [
                '@return', 'array', 'desc' => 'The new array with the '
                    . 'converted keys.'
            ]
        ],

        'get_doc_block_creator' => [
            'desc' => __($gives, ['property' => 'docBlockCreator']),
            [ '@return', 'DocBlockCreator' ]
        ],

        'get_crtr_with_indent' => [
            'desc' => __($gives, ['property' => 'crtrWithIndent']),
            [ '@return', 'DocBlockCreator' ]
        ],

        'set_max_line_length' => [
            'desc' => __($sets, [ 'what' => 'max line width' ]),
            [ '@return', 'self' ]
        ],

        'set_min_last_column_width' => [
            'desc' => __($sets, [ 'what' => 'min last column width' ]),
            [ '@return', 'self' ]
        ],

        'set_indent_width' => [
            'desc' => __($alsoSets, [ 'what' => 'an indentation width' ]),
            [ '@return', 'self' ]
        ],

        'set_use_tab_for_indentation' => [
            'desc' => __($alsoSets, [
                    'what' => 'wether or not a tab is used instead of spaces'
                ]),
            [ '@return', 'self' ]
        ]
    ]
];
