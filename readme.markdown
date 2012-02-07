# PHP Iterátorok

Gondoltam készítek egy részletes, de korántsem teljes leírást a PHP iterátorairól.

## SPL

Az iterátorokkal kapcsolatos interface-ek és osztályok az SPL (Standard PHP Library) **csomagban** találhatóak.

## Iterator

A PHP-s iterator-ok az [Iterator](http://hu.php.net/manual/en/class.iterator.php "Iterator") interface-en alapulnak, azt implementálják. Sok érdekesség nincs benne, a szokásos iterátorokkal kapcsolatos metódusokat szedi össze: current(), key(), next(), rewind(), valid().

## EmptyIterator

[EmptyIterator](http://php.net/emptyiterator)

Üres iterator.

## ArrayIterator

[ArrayIterator](http://hu.php.net/manual/en/class.arrayiterator.php)

Tömbökön és objektumokon iterálhatunk végig.

Példa:

<pre>
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
</pre>

Kimenet:

<pre>
Iterate over an object
1
Iterate over an array
1
2
3
</pre>

## DirectoryIterator

[DirectoryIterator](http://hu.php.net/manual/en/class.directoryiterator.php)

Egy könyvtár elemein mehetünk végig vele. Az elemek [SplFileInfo](http://hu.php.net/manual/en/class.splfileinfo.php) objektumok.

Könyvtár szerkezet a iterátorok bemutatására:

<pre>
|-example/b
| \-example/b/b.txt
|-example/c
\-example/a
  |-example/a/e
  | \-example/a/e/e.txt
  |-example/a/a.txt
  \-example/a/d
</pre>

Példa:

<pre>
$iterator = new DirectoryIterator('example');

foreach ($iterator as $value) {
    echo $value->getFilename(), PHP_EOL;
}
</pre>

Kimenet:

<pre>
b
..
c
.
a
</pre>

## FilesystemIterator

[FilesystemIterator](http://php.net/manual/en/class.filesystemiterator.php)

Hasonló a DirectoryIterator-hoz, abból öröklődik. A constructor alap esetben az alábbi flag-eket kapja:

* KEY\_AS_PATHNAME
* CURRENT\_AS_FILEINFO
* SKIP_DOTS

További flag-ek:

* CURRENT\_AS_PATHNAME: a current() az aktuális file elérésével tér vissza
* CURRENT\_AS_FILEINFO: a current() egy SplFileInfo objektummal tér vissza
* CURRENT\_AS_SELF: a current() az iterator-ral ($this) tér vissza
* CURRENT\_MODE_MASK: ?
* KEY\_AS_PATHNAME: a key() az aktuális file elérésével tér vissza
* KEY\_AS_FILENAME: a key() az aktuális file nevével tér vissza
* FOLLOW\_SYMLINKS: a hasChildren() követi a linkeket
* KEY\_MODE_MASK: ?
* NEW\_CURRENT_AND_KEY: ugyanaz mint a FilesystemIterator::KEY\_AS_FILENAME | FilesystemIterator::CURRENT\_AS_FILEINFO
* SKIP\_DOTS: átugorja a .-ot és a ..-ot
* UNIX\_PATHS: az elérési utakban /-t használ, a PHP-t futtató OS-től függetlenül (pl Windows-on)

## GlobIterator

[GlobIterator](http://php.net/manual/en/globiterator.construct.php)

A [glob()](http://php.net/manual/en/function.glob.php) függvényhez hasonló funkcionalitással rendelkező iterator.

## RecursiveDirectoryIterator

[RecursiveDirectoryIterator](http://hu.php.net/manual/en/class.recursivedirectoryiterator.php)

A DirectoryIterator-hoz hasonlóan itt is egy könyvtár elemein mehetünk végig, de rekurzívan.

Péda:
<pre>
$directoryIterator = new RecursiveDirectoryIterator(
    'example',
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
|-example/b
| \-example/b/b.txt
|-example/c
\-example/a
  |-example/a/e
  | \-example/a/e/e.txt
  |-example/a/a.txt
  \-example/a/d
</pre>

## ParentIterator

[ParenntIterator](http://www.php.net/manual/en/class.parentiterator.php)

Segítségével RecursiveIterator-okból lehet kiszűrni azon elemeket, amelyeknek nincs gyermekük. A RecursiveDirectoryIterator példához visszanyúlva, listázzuk ki csak a könyvtárakat:

<pre>
$directoryIterator = new RecursiveDirectoryIterator(
    'example',
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

Az üres könyvtárakat azért mutatja, mert ugye minden könyvtárban van egy hivatkozás önmagára (.) és a szülő könyvtárra (..), tehát van gyerekük.

<pre>
|-example/b
|-example/c
\-example/a
  |-example/a/e
  \-example/a/d
</pre>

## AppendIterator

[AppendIterator](http://www.php.net/manual/en/class.appenditerator.php)

Több belső iteratort tárol, és azokon megy végig, egymás után.

A getArrayIterator() egy ArrayIterator-ban visszaadja az összes belső iteratort.

Példa:

<pre>
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
</pre>

Kimenet:

<pre>
0 => 0
1 => 1
2 => 2
3 => 3
0 => 4
1 => 5
2 => 6

getArrayIterator()

0 => ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => 0
            [1] => 1
            [2] => 2
            [3] => 3
        )

)

1 => ArrayIterator Object
(
    [storage:ArrayIterator:private] => Array
        (
            [0] => 4
            [1] => 5
            [2] => 6
        )

)
</pre>

## MultipleIterator

[MultipleIterator](http://www.php.net/manual/en/class.multipleiterator.php)

Az AppendIterator-hoz hasonlóan itt is több iterator-on mehetünk végig, de kicsit másképpen. Minden belső iterator-ból kivesz egy elemet, majd egy tömbben összegyűjtve adja vissza iterációnként.

Flag-ek:

* MultipleIterator::MIT\_KEYS_NUMERIC: a tömb numerikusan indexelt
* MultipleIterator::MIT\_KEYS_ASSOC: a tömb asszociatív. A kulcsok az iterator-ok hozzáadásánál adhatóak meg (attachIterator).

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
Array
(
    [foo] => 1
    [bar] => 5
    [baz] => 8
)
Array
(
    [foo] => 2
    [bar] => 6
    [baz] => 9
)
Array
(
    [foo] => 3
    [bar] => 7
    [baz] => 10
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
Array
(
    [0] => 1
    [1] => 5
    [2] => 8
)
Array
(
    [0] => 2
    [1] => 6
    [2] => 9
)
Array
(
    [0] => 3
    [1] => 7
    [2] => 10
)
Array
(
    [0] => 4
    [1] =>
    [2] => 11
)
</pre>

## IteratorIterator

[IteratorIterator](http://www.php.net/manual/en/class.iteratoriterator.php)

A [Traversable] interfészt implementáló objektumokból iterátort csinál.

## NoRewindIterator

[NoRewindIterator](http://www.php.net/manual/en/class.norewinditerator.php)

Iterator, amit nem lehet rewind-olni, azaz, ha egyszer végigmentünk az összes elemen, nem lehet újra kezdeni az iterálást.

## InfiniteIterator

[InfiniteIterator](http://www.php.net/InfiniteIterator)

Olyan iterátor, amellyel a végtelenségig iterálhatunk az elemeken, nem kell a rewind-ot meghívnunk.

## FilterIterator

[FilterIterator](http://www.php.net/manual/en/class.filteriterator.php)

A FilterIterator egy belső iterátor elemiből tud szűrni. A FilterIterator egy abstract osztály, amelyben egy darab abstract metódus van, az accept(). Az accept() visszatérési értéke boolean:

* true esetén az iterator elfogadja az elemet
* false esetén eldobja

Példa:

<pre>
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
</pre>

Kimenet:

<pre>
1
3
5
7
9
</pre>