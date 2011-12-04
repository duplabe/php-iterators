<?php

$arrayIterator1 = new ArrayIterator(array('1','2','3','4',));
$arrayIterator2 = new ArrayIterator(array('5','6','7',));
$arrayIterator3 = new ArrayIterator(array('8','9','10','11',));

$miterator = new MultipleIterator(
    MultipleIterator::MIT_KEYS_NUMERIC | MultipleIterator::MIT_NEED_ANY
);
$miterator->attachIterator($arrayIterator1, 'foo');
$miterator->attachIterator($arrayIterator2, 'bar');
$miterator->attachIterator($arrayIterator3, 'baz');

foreach ($miterator as $item) {
    echo var_export($item, true), PHP_EOL;
}