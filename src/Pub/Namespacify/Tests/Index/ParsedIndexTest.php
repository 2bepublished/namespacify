<?php

namespace Pub\Namespacify\Tests\Index;

use Pub\Namespacify\Index\ParsedIndex;

class ParsedIndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Pub\Namespacify\Index\ParsedIndex::add
     */
    public function testAdd()
    {
        $index = new ParsedIndex();
        $index->add(array('class' => 'World', 'namespace' => 'Hello', 'code' => 'class World { }'));
        $this->assertTrue($index->has('World'), '->add() adds a new item to the parsed index.');
    }

    /**
     * @covers Pub\Namespacify\Index\ParsedIndex::add
     */
    public function testAddThrowsException()
    {
        $index = new ParsedIndex();
        try {
            $index->add(array('namespace' => 'Hello', 'code' => 'class World { }'));
            $this->fail('->add() thrown an \InvalidArgumentException if the "class" element is missing.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\InvalidArgumentException',
                $e,
                '->add() thrown an \InvalidArgumentException if the "class" element is missing.'
            );
            $this->assertEquals(
                'Missing "class" element.',
                $e->getMessage(),
                '->add() thrown an \InvalidArgumentException if the "class" element is missing.'
            );
        }

        try {
            $index->add(array('class' => 'World', 'code' => 'class World { }'));
            $this->fail('->add() thrown an \InvalidArgumentException if the "namespace" element is missing.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\InvalidArgumentException',
                $e,
                '->add() thrown an \InvalidArgumentException if the "namespace" element is missing.'
            );
            $this->assertEquals(
                'Missing "namespace" element.',
                $e->getMessage(),
                '->add() thrown an \InvalidArgumentException if the "namespace" element is missing.'
            );
        }

        try {
            $index->add(array('class' => 'World', 'namespace' => 'Hello'));
            $this->fail('->add() thrown an \InvalidArgumentException if the "code" element is missing.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\InvalidArgumentException',
                $e,
                '->add() thrown an \InvalidArgumentException if the "code" element is missing.'
            );
            $this->assertEquals(
                'Missing "code" element.',
                $e->getMessage(),
                '->add() thrown an \InvalidArgumentException if the "code" element is missing.'
            );
        }
    }

    /**
     * @covers Pub\Namespacify\Index\ParsedIndex::has
     */
    public function testHas()
    {
        $index = new ParsedIndex();
        $index->add(array('class' => 'World', 'namespace' => 'Hello', 'code' => 'class World { }'));
        $this->assertTrue($index->has('World'), '->has() returns TRUE if a parsed item does exist.');
        $this->assertFalse($index->has('Invalid'), '->has() returns FALSE if a parsed item does not exist.');
    }

    /**
     * @covers Pub\Namespacify\Index\ParsedIndex::get
     */
    public function testGet()
    {
        $index = new ParsedIndex();
        $index->add(array('class' => 'World', 'namespace' => 'Hello', 'code' => 'class World { }'));
        $this->assertEquals(
            array('class' => 'World', 'namespace' => 'Hello', 'code' => 'class World { }'),
            $index->get('World'),
            '->get() returns a parsed item.'
        );
    }

    /**
     * @covers Pub\Namespacify\Index\ParsedIndex::get
     */
    public function testRemove()
    {
        $index = new ParsedIndex();
        $index->add(array('class' => 'World', 'namespace' => 'Hello', 'code' => 'class World { }'));
        $index->remove('World');
        $this->assertFalse($index->has('World'), '->remove() removes an existing parsed item.');
    }

    /**
     * @covers Pub\Namespacify\Index\ParsedIndex::getAll
     */
    public function testGetAll()
    {
        $index = new ParsedIndex();
        $index->add(array('class' => 'World', 'namespace' => 'Hello', 'code' => 'class World { }'));
        $index->add(array('class' => 'Moon', 'namespace' => 'Hello', 'code' => 'class Moon { }'));
        $items = $index->getAll();
        $this->assertCount(2, $items, '->getAll() returns all items from the parsed index.');
        $this->assertEquals('World', $items['World']['class'], '->getAll() returns all items from the parsed index.');
        $this->assertEquals('Moon', $items['Moon']['class'], '->getAll() returns all items from the parsed index.');
    }
}
