<?php

/**
 * IndexerInterface
 *
 * @category  indexer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */

namespace Pub\Namespacify\Indexer;

/**
 * IndexerInterface
 *
 * @category  indexer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */
 // @codeCoverageIgnore
interface IndexerInterface
{
    /**
     * Index the given directory.
     *
     * @param string $directory The directory to index
     *
     * @return Pub\Namespacify\Index\IndexInterface The index
     *
     * @throws Pub\Namespacify\Exception\NamespaceFoundException when a namespace statement is found in class file.
     */
    public function index($directory);
}