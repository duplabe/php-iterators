<?php

$directoryIterator = new RecursiveDirectoryIterator(
    'pelda',
    RecursiveDirectoryIterator::SKIP_DOTS
);
$treeIterator = new RecursiveTreeIterator(
    $directoryIterator
);
foreach ($treeIterator as $key => $node) {
    echo $node, PHP_EOL;
}