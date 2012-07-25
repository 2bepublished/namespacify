<?php

namespace Pub\Namespacify\Tests\Generator;

use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;

use Pub\Namespacify\Generator\Psr0Generator;
use Pub\Namespacify\Index\ParsedIndex;

class Psr0GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $index = new ParsedIndex();
        $index->add(array('class' => 'World', 'namespace' => 'Hello\\World', 'code' => "class World \n{\n}"));
        $index->add(array(
            'class' => 'Moon',
            'namespace' => 'Hello\\Moon',
            'code' => "class Moon \n{function a(){new World();}\n}"
        ));
        $generator = new Psr0Generator();
        $generator->setFilesystem(new Filesystem());

        $that = $this;
        $generator->setWriter(function ($file, $code) use ($that) {
            if ('World.php' === substr($file, -9)) {
                $that->assertEquals('./generated/Hello/World/World.php', $file);
                $that->assertEquals("<?php\n\nnamespace Hello\\World;\n\nclass World \n{\n}\n", $code);
            } elseif ('Moon.php' === substr($file, -8)) {
                $that->assertEquals('./generated/Hello/Moon/Moon.php', $file);
                $that->assertEquals(
                    "<?php\n\nnamespace Hello\\Moon;\n\nuse Hello\\World\\World;\n\n" .
                        "class Moon \n{function a(){new World();}\n}\n",
                    $code
                );
            }
        });
        $generator->generate($index, './generated');
    }
}

class Filesystem extends BaseFilesystem
{
    public function mkdir($dirs, $mode = 0777)
    {
    }
}
