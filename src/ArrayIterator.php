<?php

class Example
{
    public $a;
    protected $b;
    private $_c;

    public function __construct($a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->_c = $c;
    }
}

echo 'Iterate over an object', PHP_EOL;

$iterator = new ArrayIterator(new Example(1, 2, 3));

foreach ($iterator as $item)
{
    echo $item, PHP_EOL;
}

echo 'Iterate over an array', PHP_EOL;

$iterator = new ArrayIterator(array(1,2,3));

foreach ($iterator as $item)
{
    echo $item, PHP_EOL;
}
