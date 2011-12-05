<?php

$directoryIterator = new RecursiveDirectoryIterator(
    'example',
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