<?php

/**
 * TransformerInterface
 *
 * @category  transformer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Transformer;

/**
 * TransformerInterface
 *
 * @category  transformer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
 // @codeCoverageIgnore
interface TransformerInterface
{
    /**
     * Transforms the given value
     *
     * @param mixed $value The value
     *
     * @return mixed The transformed value
     */
    public function transform($value);
}