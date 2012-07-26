<?php

/**
 * Psr0GeneratorTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Tests\Generator;

use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;

use Pub\Namespacify\Generator\Psr0Generator;
use Pub\Namespacify\Index\ParsedIndex;
use Pub\Namespacify\Transformer\TransformerChain;

/**
 * Psr0GeneratorTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class Psr0GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public $loggerCalled;

    public function setUp()
    {
        $this->loggerCalled = false;
    }

    /**
     * @covers Pub\Namespacify\Generator\Psr0Generator::generate
     * @covers Pub\Namespacify\Generator\Psr0Generator::generateUseStatements
     * @covers Pub\Namespacify\Generator\Psr0Generator::setWriter
     * @covers Pub\Namespacify\Generator\Psr0Generator::getWriter
     * @covers Pub\Namespacify\Generator\Psr0Generator::setLoggingCallback
     * @covers Pub\Namespacify\Generator\Psr0Generator::setTransformer
     */
    public function testGenerate()
    {
        $index = new ParsedIndex();
        $index->add(array('class' => 'World', 'namespace' => 'Hello\\World', 'code' => "class World \n{\n}"));
        $index->add(array(
            'class' => 'Moon',
            'namespace' => 'Hello\\Moon',
            'code' => "class Moon \n{function a(array \$a){new World();}\n}"
        ));
        $index->add(array(
            'class'     => 'Mars',
            'namespace' => 'Hello\\Moon',
            'code'      => "class Mars \n{function a(World \$world){throw new Exception();}\n}"
        ));
        $index->add(array(
            'class'     => 'Venus',
            'namespace' => 'Hello\\Venus',
            'code'      => "class Venus extends Mars \n{\n}"
        ));
        $generator = new Psr0Generator();
        $generator->setFilesystem(new Filesystem());
        $generator->setTransformer(new TransformerChain());

        $that = $this;
        $generator->setWriter(function ($file, $code) use ($that) {
            if ('World.php' === substr($file, -9)) {
                $that->assertEquals('./generated/Test/Hello/World/World.php', $file);
                $that->assertEquals("<?php\n\nnamespace Test\\Hello\\World;\n\nclass World \n{\n}\n", $code);
            } elseif ('Moon.php' === substr($file, -8)) {
                $that->assertEquals('./generated/Test/Hello/Moon/Moon.php', $file);
                $that->assertEquals(
                    "<?php\n\nnamespace Test\\Hello\\Moon;\n\nuse Test\\Hello\\World\\World;\n\n" .
                        "class Moon \n{function a(array \$a){new World();}\n}\n",
                    $code
                );
            } elseif ('Mars.php' === substr($file, -8)) {
                $that->assertEquals('./generated/Test/Hello/Moon/Mars.php', $file);
                $that->assertEquals(
                    "<?php\n\nnamespace Test\\Hello\\Moon;\n\nuse \\Exception as Exception;\n" .
                        "use Test\\Hello\\World\\World;\n\n" .
                        "class Mars \n{function a(World \$world){throw new Exception();}\n}\n",
                    $code
                );
            } elseif ('Venus.php' === substr($file, -9)) {
                $that->assertEquals('./generated/Test/Hello/Venus/Venus.php', $file);
                $that->assertEquals(
                    "<?php\n\nnamespace Test\\Hello\\Venus;\n\nuse Test\\Hello\\Moon\\Mars;\n\n" .
                        "class Venus extends Mars \n{\n}\n",
                    $code);
            }
        });
        $generator->setLoggingCallback(function ($namespace, $class, $file) use ($that) {
            $that->loggerCalled = true;
        });
        $generator->generate($index, './generated', 'Test');

        if (!$this->loggerCalled) {
            $this->fail('->generate() calls logging callback.');
        } else {
            $this->assertTrue(true, '->generate() calls logging callback.');
        }
    }

    /**
     * @covers Pub\Namespacify\Generator\Psr0Generator::setFilesystem
     * @covers Pub\Namespacify\Generator\Psr0Generator::getFilesystem
     */
    public function testGetSetFilesystem()
    {
        $filesystem = new BaseFilesystem();
        $generator = new Psr0Generator();
        $generator->setFilesystem($filesystem);
        $this->assertEquals($filesystem, $generator->getFilesystem(), '->setFilesystem() sets the Filesystem.');
    }
}

class Filesystem extends BaseFilesystem
{
    public function mkdir($dirs, $mode = 0777)
    {
    }
}
