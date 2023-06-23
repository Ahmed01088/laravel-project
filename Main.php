<?php

$ids = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
$idsO = array(1, 2, 6, 3, 4, 5);

$differences = array();

foreach ($ids as $value) {
    if (!in_array($value, $idsO)) {
        $differences[] = $value;
    }
}

print_r($differences);
