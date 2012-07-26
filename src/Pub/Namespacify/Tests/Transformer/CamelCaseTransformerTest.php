<?php

/**
 * CamelCaseTransformerTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */

namespace Pub\Namespacify\Tests\Transformer;

use Pub\Namespacify\Transformer\CamelCaseTransformer;

/**
 * CamelCaseTransformerTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */
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
