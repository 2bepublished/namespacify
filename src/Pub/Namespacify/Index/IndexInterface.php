<?php

/**
 * IndexInterface
 *
 * PHP Version 5.3.10
 *
 * @category  index
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 *
 * @codeCoverageIgnore
 */

namespace Pub\Namespacify\Index;

/**
 * IndexInterface
 *
 * @category  index
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
 // @codeCoverageIgnore
interface IndexInterface
{
    /**
     * Adds an item to the index.
     *
     * @param array $item The item
     *
     * @return \Pub\Namespacify\Index\IndexInterface self
     */
    public function add(array $item);

    /**
     * Returns if the item with the given key exists in the index.
     *
     * @param mixed $key The key
     *
     * @return boolean TRUE if the item exists, FALSE if not
     */
    public function has($key);

    /**
     * Returns the item with the given key.
     *
     * @param mixed $key The key
     *
     * @return array The item
     */
    public function get($key);

    /**
     * Removes the item with the given key from the index.
     *
     * @param mixed $key The key
     *
     * @return \Pub\Namespacify\Index\IndexInterface self
     */
    public function remove($key);

    /**
     * Returns all items from the index.,
     *
     * @return array All items
     */
    public function getAll();
}
