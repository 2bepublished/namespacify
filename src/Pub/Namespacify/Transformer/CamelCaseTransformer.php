<?php

/**
 * CamelCaseTransformer
 *
 * @category  transformer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */

namespace Pub\Namespacify\Transformer;

/**
 * CamelCaseTransformer
 *
 * @category  transformer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */
class CamelCaseTransformer implements TransformerInterface
{
    /** {@inheritDoc} */
    public function transform($value)
    {
        $value = $this->underscoreToCamelCase($value);
        return $value;
    }

    /**
     * Transforms underscores syntax into CamelCase syntax.
     *
     * @param string $value The value
     *
     * @return string The value in CamelCase syntax
     */
    protected function underscoreToCamelCase($value)
    {
        $value = str_replace('_', ' ', $value);
        $value = ucwords($value);
        $value = str_replace(' ', '', $value);
        return $value;
    }
}
