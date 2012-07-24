<?php

/**
 * Parsedndex
 *
 * PHP Version 5.3.10
 *
 * @category  index
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Index;

/**
 * Parsedndex
 *
 * @category  index
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class ParsedIndex implements IndexInterface
{
    /** @var array The index */
    protected $index = array();

    /** {@inheritDoc} */
    public function add(array $item)
    {
        if (!isset($item['class'])) {
            throw new \InvalidArgumentException('Missing "class" element.');
        }
        if (!isset($item['namespace'])) {
            throw new \InvalidArgumentException('Missing "namespace" element.');
        }
        if (!isset($item['code'])) {
            throw new \InvalidArgumentException('Missing "code" element.');
        }

        $this->index[$item['class']] = $item;

        return $this;
    }

    /** {@inheritDoc} */
    public function has($key)
    {
        return isset($this->index[$key]);
    }

    /** {@inheritDoc} */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException(
                sprintf('Item with key "%d" does not exist.', $key)
            );
        }

        return $this->index[$key];
    }

    /** {@inheritDoc} */
    public function remove($key)
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException(
                sprintf('Item with key "%d" does not exist.', $key)
            );
        }

        unset($this->index[$key]);

        return $this;
    }

    /** {@inheritDoc} */
    public function getAll()
    {
        return $this->index;
    }
}
