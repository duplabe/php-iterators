<?php

$directoryIterator = new RecursiveDirectoryIterator(
    'pelda',
    RecursiveDirectoryIterator::SKIP_DOTS
);
$treeIterator = new RecursiveTreeIterator(
    new ParentIterator(
        $directoryIterator
    )
);
foreach ($treeIterator as $key => $node) {
    echo $node, PHP_EOL;
}