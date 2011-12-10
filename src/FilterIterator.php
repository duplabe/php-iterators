<?php

class OddFilterIterator extends FilterIterator
{

    public function accept()
    {
        $current = $this->current();

        return is_integer($current) && $current % 2;
    }

}

$iterator = new AppendIterator();
$iterator->append(new ArrayIterator(range(0, 10)));
$iterator->append(new ArrayIterator(array('a', 'b')));

$filterIterator = new OddFilterIterator($iterator);

foreach ($filterIterator as $item) {
    echo $item, PHP_EOL;
}