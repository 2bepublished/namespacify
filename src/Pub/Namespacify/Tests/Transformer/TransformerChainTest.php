<?php

/**
 * TransformerChainTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */

namespace Pub\Namespacify\Tests\Transformer;

use Pub\Namespacify\Transformer\TransformerChain;
use Pub\Namespacify\Transformer\TransformerInterface;

/**
 * TransformerChainTest
 *
 * @category  test
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */
class TransformerChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Pub\Namespacify\Transformer\TransformerChain::transform
     * @covers Pub\Namespacify\Transformer\TransformerChain::addTransformer
     */
    public function testTransform()
    {
        $transformer = new TransformerChain();
        $transformer->addTransformer(new Transformer1());
        $transformer->addTransformer(new Transformer2());
        $this->assertEquals(
            'h3llo',
            $transformer->transform('HELLO'),
            '->transform() calls transform() method of all attached transformers.'
        );
    }
}

class Transformer1 implements TransformerInterface
{
    public function transform($value)
    {
        return strtolower($value);
    }
}

class Transformer2 implements TransformerInterface
{
    public function transform($value)
    {
        return str_replace('e', '3', $value);
    }
}
