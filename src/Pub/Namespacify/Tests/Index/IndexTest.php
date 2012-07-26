<?php

/**
 * IndexTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Tests\Index;

use Pub\Namespacify\Index\Index;

/**
 * IndexTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class IndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Pub\Namespacify\Index\Index::add
     */
    public function testHasGetAdd()
    {
        $index = new Index();
        $index->add(array('file' => 'Hello/World.php', 'classes' => array('HelloWorld', 'Hello')));
        $this->assertTrue($index->has('Hello_World.php'), '->add() adds a new item to the index.');
    }

    /**
     * @covers Pub\Namespacify\Index\Index::add
     */
    public function testAddThrowsException()
    {
        $index = new Index();
        try {
            $index->add(array('file' => 'Hello.php'));
            $this->fail('->add() thrown an \InvalidArgumentException if the "classes" element is missing.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\InvalidArgumentException',
                $e,
                '->add() thrown an \InvalidArgumentException if the "classes" element is missing.'
            );
            $this->assertEquals(
                'Missing "classes" element.',
                $e->getMessage(),
                '->add() thrown an \InvalidArgumentException if the "classes" element is missing.'
            );
        }

        try {
            $index->add(array('classes' => array('Hello')));
            $this->fail('->add() thrown an \InvalidArgumentException if the "file" element is missing.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\InvalidArgumentException',
                $e,
                '->add() thrown an \InvalidArgumentException if the "file" element is missing.'
            );
            $this->assertEquals(
                'Missing "file" element.',
                $e->getMessage(),
                '->add() thrown an \InvalidArgumentException if the "file" element is missing.'
            );
        }
    }

    /**
     * @covers Pub\Namespacify\Index\Index::has
     */
    public function testHas()
    {
        $index = new Index();
        $index->add(array('file' => 'Hello.php', 'classes' => array('Hello')));
        $this->assertTrue($index->has('Hello.php'), '->has() returns TRUE if an item does exist.');
        $this->assertFalse($index->has('Invalid.php'), '->has() returns FALSE if an item does not exist.');
    }

    /**
     * @covers Pub\Namespacify\Index\Index::get
     */
    public function testGet()
    {
        $index = new Index();
        $index->add(array('file' => 'Hello.php', 'classes' => array('Hello')));
        $this->assertEquals(
            array('file'      => 'Hello.php', 'classes'   => array('Hello')),
            $index->get('Hello.php'),
            '->get() returns an item.'
        );

        try {
            $index->get('invalid');
            $this->fail('->get() thrown an \InvalidArgumentException since the item does not exist.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\InvalidArgumentException',
                $e,
                '->get() thrown an \InvalidArgumentException since the item does not exist.'
            );
            $this->assertEquals(
                'Item with key "invalid" does not exist.',
                $e->getMessage(),
                '->get() thrown an \InvalidArgumentException since the item does not exist.'
            );
        }
    }

    /**
     * @covers Pub\Namespacify\Index\Index::remove
     */
    public function testRemove()
    {
        $index = new Index();
        $index->add(array('file' => 'Hello.php', 'classes' => array('Hello')));
        $index->remove('Hello.php');
        $this->assertFalse($index->has('Hello.php'), '->remove() removes an existing item.');

        try {
            $index->remove('invalid');
            $this->fail('->remove() thrown an \InvalidArgumentException since the item does not exist.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(
                '\InvalidArgumentException',
                $e,
                '->remove() thrown an \InvalidArgumentException since the item does not exist.'
            );
            $this->assertEquals(
                'Item with key "invalid" does not exist.',
                $e->getMessage(),
                '->remove() thrown an \InvalidArgumentException since the item does not exist.'
            );
        }
    }

    /**
     * @covers Pub\Namespacify\Index\Index::getAll
     */
    public function testGetAll()
    {
        $index = new Index();
        $index->add(array('file' => 'Hello.php', 'classes' => array('Hello')));
        $index->add(array('file' => 'World.php', 'classes' => array('World')));
        $items = $index->getAll();
        $this->assertCount(2, $items, '->getAll() returns all items from the index.');
        $this->assertEquals('Hello.php', $items['Hello.php']['file'], '->getAll() returns all items from the index.');
        $this->assertEquals('World.php', $items['World.php']['file'], '->getAll() returns all items from the index.');
    }
}
