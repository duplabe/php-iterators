<?php

$iterator = new GlobIterator('example/b');

foreach ($iterator as $value) {
    echo $value->getFilename(), PHP_EOL;
}
