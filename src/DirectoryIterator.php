<?php

$iterator = new DirectoryIterator('example');

foreach ($iterator as $value) {
    echo $value->getFilename(), PHP_EOL;
}