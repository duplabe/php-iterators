<?php

$limitIterator = new LimitIterator(
    new ArrayIterator(range(0, 10)),
    5,
    3
);

foreach ($limitIterator as $item) {
    echo $item, PHP_EOL;
}