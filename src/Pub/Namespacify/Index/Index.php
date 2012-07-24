<?php

/**
 * Index
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
 * Index
 *
 * @category  index
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class Index implements IndexInterface
{
    /** @var array */
    protected $index = array();

    public function add(array $item)
    {
        if (!isset($item['file'])) {
            throw new \InvalidArgumentException('Missing "file" element.');
        }
        if (!isset($item['classes'])) {
            throw new \InvalidArgumentException('Missing "classes" element.');
        }

        $this->index[] = $item;

        return $this;
    }
}