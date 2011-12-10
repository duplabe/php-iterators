<?php

$iterator1 = new ArrayIterator(range(0, 3));
$iterator2 = new ArrayIterator(range(4, 6));

$appendIterator = new AppendIterator();
$appendIterator->append($iterator1);
$appendIterator->append($iterator2);

foreach ($appendIterator as $key => $value) {
    echo $key, ' => ', print_r($value, true), PHP_EOL;
}

echo PHP_EOL, 'getArrayIterator()', PHP_EOL, PHP_EOL;

foreach ($appendIterator->getArrayIterator() as $key => $value) {
    echo $key, ' => ', print_r($value, true), PHP_EOL;
}
