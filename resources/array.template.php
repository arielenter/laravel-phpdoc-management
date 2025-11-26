<?php

use Arielenter\LaravelPhpdocManagement\FilePhpdocManager;

$:original = [
	'properties' => [ ':property_one' => ':sumary', ':propery_two' => ':sumary' ],
    'constants' => [ ':my_constant' => ':sumary' ],
	'methods' => [ ':method_one' => ':sumary.', ':method_two' => ':sumary.' ]
];

$filePhpdocManager = new FilePhpdocManager(':file');
$:new = $filePhpdocManager->translateKeys($:original);
