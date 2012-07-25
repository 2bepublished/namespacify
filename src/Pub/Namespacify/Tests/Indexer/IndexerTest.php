<?php

namespace Pub\Namespacify\Tests\Indexer;

use Pub\Namespacify\Indexer\Indexer as BaseIndexer;
use Pub\Namespacify\Index\Index;
use Symfony\Component\Finder\SplFileInfo as BaseSplFileInfo;

class IndexerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Pub\Namespacify\Indexer\Indexer::index
     */
    public function testIndex()
    {
        $indexer = new Indexer();
        $indexer->setIndex(new Index());

        $index = $indexer->index('Hello');
        $items = $index->getAll();
        $this->assertCount(2, $items, '->index() indexes all matching files.');
        $this->assertContains('World', $items['Hello_World.php']['classes'], '->index() indexes all matching files.');
        $this->assertContains('Moon', $items['Hello_Moon.php']['classes'], '->index() indexes all matching files.');
    }
}

class Indexer extends BaseIndexer
{
    protected function getFileIterator($directory)
    {
        return new \ArrayIterator(array(
            new SplFileInfo('Hello/World.php', 'Hello', 'Hello/World.php'),
            new SplFileInfo('Hello/Moon.php', 'Hello', 'Hello/Moon.php'),
            new SplFileInfo('Hello/Nothing.php', 'Hello', 'Hello/Nothing.php')
        ));
    }
}

class SplFileInfo extends BaseSplFileInfo
{
    public function getContents()
    {
        switch ($this->getRelativePathname()) {
            case 'Hello/World.php':
                $class = 'World';
                break;
            case 'Hello/Moon.php':
                $class = 'Moon';
                break;
            case 'Hello/Nothing.php':
                return '';
        }
        return "<?php\n\nclass " . $class . " {\n}\n";
    }
}