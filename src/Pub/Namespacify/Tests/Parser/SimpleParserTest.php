<?php

namespace Pub\Namespacify\Tests\Parser;

use Symfony\Component\Finder\SplFileInfo as BaseSplFileInfo;

use Pub\Namespacify\Index\Index;
use Pub\Namespacify\Parser\SimpleParser;

class SimpleParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Pub\Namespacify\Parser\SimpleParser::parse
     */
    public function testParse()
    {
        $index = new Index();
        $index
            ->add(array(
                'file'      => new SplFileInfo('Hello/World.php', 'Hello', 'Hello/World.php'),
                'classes'   => array('World')
            ))
            ->add(array(
                'file'      => new SplFileInfo('Hello/Moon.php', 'Hello', 'Hello/Moon.php'),
                'classes'   => array('Moon', 'Mars')
            ))
        ;
        $parser = new SimpleParser();
        $items = $parser->parse($index)->getAll();
        $this->assertCount(3, $items, '->parse() parses all classes in the index and extracts the code.');
        $this->assertEquals('World', $items['Hello_World_World']['class']);
        $this->assertEquals('Hello\\World', $items['Hello_World_World']['namespace']);
        $this->assertEquals("class World {\n}", $items['Hello_World_World']['code']);
        $this->assertEquals('Moon', $items['Hello_Moon_Moon']['class']);
        $this->assertEquals('Hello\\Moon', $items['Hello_Moon_Moon']['namespace']);
        $this->assertEquals("class Moon {\n}", $items['Hello_Moon_Moon']['code']);
        $this->assertEquals('Mars', $items['Hello_Moon_Mars']['class']);
        $this->assertEquals('Hello\\Moon', $items['Hello_Moon_Mars']['namespace']);
        $this->assertEquals("class Mars {\n}", $items['Hello_Moon_Mars']['code']);
    }
}

class SplFileInfo extends BaseSplFileInfo
{
    public function getContents()
    {
        switch ($this->getRelativePathname()) {
            case 'Hello/World.php':
                return "<?php\n\nclass World {\n}\n";
            case 'Hello/Moon.php':
                return "<?php\n\nclass Moon {\n}\n\nclass Mars {\n}\n";
        }
    }
}