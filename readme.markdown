# PHP Iterátorok #

Gondoltam készítek egy részletes, de korántsem teljes leírást a PHP iterátorairól.

## SPL ##

Az iterátorokkal kapcsolatos interface-ek és osztályok az SPL (Standard PHP Library) **csomgban** találhatóak.

## Interface ##

### Iterator ###

A PHP-s iterator-ok az [Iterator](http://hu.php.net/manual/en/class.iterator.php "Iterator") interface-en alapulnak, azt implementálják. Sok érdekesség nincs benne, a szokásos iterátorokkal kapcsolatos metódusokat szedi össze: current(), key(), next(), rewind(), valid().

Könyvtár szerkezet a rekurzív iterátorok bemutatására:

<pre>
|-pelda/b
| \-pelda/b/b.txt
|-pelda/c
\-pelda/a
  |-pelda/a/e
  | \-pelda/a/e/e.txt
  |-pelda/a/a.txt
  \-pelda/a/d
</pre>

### DirectoryIterator ###

Egy könyvtár elemein mehetünk végig vele

### RecursiveDirectoryIterator ###

A DirectoryIterator-hoz hasonlóan itt is egy könyvtár elemein mehetünk végig, de rekurzívan.

Péda:
<pre>
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
</pre>

Kimenet:

<pre>
|-pelda/b
| \-pelda/b/b.txt
|-pelda/c
\-pelda/a
  |-pelda/a/e
  | \-pelda/a/e/e.txt
  |-pelda/a/a.txt
  \-pelda/a/d
</pre>

### ParentIterator ###

Segítségével RecursiveIterator-okból lehet kiszűrni azon elemeket, amelyeknek nincs gyermekük. A RecursiveDirectoryIterator példához visszanyúlva, listázzuk ki csak a könyvtárakat:

<pre>
$directoryIterator = new RecursiveDirectoryIterator(
    'pelda',
    RecursiveDirectoryIterator::SKIP_DOTS
);
$treeIterator = new RecursiveTreeIterator(
	new ParentIterator($directoryIterator)
);
foreach ($treeIterator as $node) {
    echo $node . PHP_EOL;
};
</pre>

Kimenet:

<pre>
|-pelda/b
|-pelda/c
\-pelda/a
  |-pelda/a/e
  \-pelda/a/d
</pre>

#### MultipleIterator ####

Az AppendIterator-hoz hasonlóan itt is több iterator-on mehetünk végig, de kicsit másképpen. Minden belső iterator-ból kivesz egy elemet, majd egy tömbben összegyűjtve adja vissza iterációnként. 

Flag-ek:

* MultipleIterator::MIT\_KEYS_NUMERIC: a tömb numerikusan indexelt
* MultipleIterator::MIT_KEYS_ASSOC: a tömb asszociatív. A kulcsok az iterator-ok hozzáadásánál adhatóak meg (attachIterator).

A belső iterator-ok elemszáma különböző lehet, ezért két flag-gel állíthatjuk be, hogy mi történjen akkor, ha valamelyikből elfogynak az elemek:

* MultipleIterator::MIT\_NEED_ANY: annyi elemet ad vissza, amennyit tud, a hiányzó elemeket null-okkal tölti fel.
* MultipleIterator::MIT\_NEED_ALL: ha valamelyik iterator-ból elfogynak az elemek, a MultipleIterator leáll

Példa:

<pre>
$arrayIterator1 = new ArrayIterator(array('1','2','3','4',));
$arrayIterator2 = new ArrayIterator(array('5','6','7',));
$arrayIterator3 = new ArrayIterator(array('8','9','10','11',));

$miterator = new MultipleIterator(
    MultipleIterator::MIT_KEYS_ASSOC | MultipleIterator::MIT_NEED_ALL
);
$miterator->attachIterator($arrayIterator1, 'foo');
$miterator->attachIterator($arrayIterator2, 'bar');
$miterator->attachIterator($arrayIterator3, 'baz');

foreach ($miterator as $item) {
    echo var_export($item, true), PHP_EOL;
}
</pre>

Kimenet:

<pre>
array (
  'foo' => '1',
  'bar' => '5',
  'baz' => '8',
)
array (
  'foo' => '2',
  'bar' => '6',
  'baz' => '9',
)
array (
  'foo' => '3',
  'bar' => '7',
  'baz' => '10',
)
</pre>

Példa:

<pre>
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
</pre>

Kimenet:

<pre>
array (
  0 => '1',
  1 => '5',
  2 => '8',
)
array (
  0 => '2',
  1 => '6',
  2 => '9',
)
array (
  0 => '3',
  1 => '7',
  2 => '10',
)
array (
  0 => '4',
  1 => NULL,
  2 => '11',
)
</pre>

NoRewindIterator

Iterator, amit nem lehet rewind-olni, azaz, ha egyszer végigmentünk az összes elemen, nem lehet újra kezdeni az iterálást.