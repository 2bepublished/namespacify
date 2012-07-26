<?php

namespace Pub\Namespacify\Tests\Indexer;

use Pub\Namespacify\Indexer\Indexer as BaseIndexer;
use Pub\Namespacify\Index\Index;
use Symfony\Component\Finder\Finder;
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
        $indexer->setFiles(array(
            new SplFileInfo('Hello/World.php', 'Hello', 'Hello/World.php'),
            new SplFileInfo('Hello/Moon.php', 'Hello', 'Hello/Moon.php'),
            new SplFileInfo('Hello/Nothing.php', 'Hello', 'Hello/Nothing.php')
        ));

        $index = $indexer->index('Hello');
        $items = $index->getAll();
        $this->assertCount(2, $items, '->index() indexes all matching files.');
        $this->assertContains('World', $items['Hello_World.php']['classes'], '->index() indexes all matching files.');
        $this->assertContains('Moon', $items['Hello_Moon.php']['classes'], '->index() indexes all matching files.');
    }

    /**
     * @covers Pub\Namespacify\Indexer\Indexer::index
     * @covers Pub\Namespacify\Exception\NamespaceExistsException
     */
    public function testIndexThrowsException()
    {
        $indexer = new Indexer();
        $indexer->setIndex(new Index());
        $indexer->setFiles(array(
            new SplFileInfo('Hello/Namespace.php', 'Hello', 'Hello/Namespace.php')
        ));

        try {
            $index = $indexer->index('Hello');
            $this->fail('->index() thrown an exception since the code contained namespaces.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\Pub\Namespacify\Exception\NamespaceExistsException',
                $e,
                '->index() thrown an exception since the code contained namespaces. [CorrectExceptionClass]');
            $this->assertEquals(
                'Found namespace "Hello\\Namespace" in file "Hello/Namespace.php"',
                $e->getMessage(),
                '->index() thrown an exception since the code contained namespaces. [CorrectExceptionMessage]'
            );
        }
    }

    /**
     * @covers Pub\Namespacify\Indexer\Indexer::setIndex
     * @covers Pub\Namespacify\Indexer\Indexer::getIndex
     */
    public function testGetSetIndex()
    {
        $indexer = new Indexer();
        $index = new Index();
        $indexer->setIndex($index);
        $this->assertEquals($index, $indexer->getIndex(), '->setIndex() sets the index.');
    }

    /**
     * @covers Pub\Namespacify\Indexer\Indexer::setFinder
     * @covers Pub\Namespacify\Indexer\Indexer::getFinder
     */
    public function testGetSetFinder()
    {
        $indexer = new Indexer();
        $finder = new Finder();
        $indexer->setFinder($finder);
        $this->assertEquals($finder, $indexer->getFinder(), '->setFinder() sets the finder.');
    }
}

class Indexer extends BaseIndexer
{
    private $files;

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    protected function getFileIterator($directory)
    {
        return new \ArrayIterator($this->files);
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
            case 'Hello/Namespace.php':
                return "<?php\n\nnamespace Hello\\Namespace;\n\nclass Namespace {\n}\n";
        }
        return "<?php\n\nclass " . $class . " {\n}\n";
    }
}