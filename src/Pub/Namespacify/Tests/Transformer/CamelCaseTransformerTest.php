<?php

namespace Pub\Namespacify\Tests\Transformer;

use Pub\Namespacify\Transformer\CamelCaseTransformer;

class CamelCaseTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Pub\Namespacify\Transformer\CamelCaseTransformer::transform
     * @covers Pub\Namespacify\Transformer\CamelCaseTransformer::underscoreToCamelCase
     */
    public function testTransform()
    {
        $transformer = new CamelCaseTransformer();
        $this->assertEquals(
            'CamelCase',
            $transformer->transform('camel_case'),
            '->transform() transforms underscore syntax into CamelCase syntax.'
        );
    }
}
