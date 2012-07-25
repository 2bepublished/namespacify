<?php

/**
 * TransformerChain
 *
 * @category  transformer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Transformer;

/**
 * TransformerChain
 *
 * @category  transformer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class TransformerChain implements TransformerInterface
{
    /** @var array Transformers */
    protected $transformers = array();

    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
        return $this;
    }

    /**
     * Applies all transformers to the given value.
     *
     * @param mixed $value The value
     *
     * @return mixed The transformed value
     */
    public function transform($value)
    {
        foreach ($this->transformers as $transformer) {
            $value = $transformer->transform($value);
        }
        return $value;
    }
}
