<?php

/**
 * IndexerInterface
 *
 * PHP Version 5.3.10
 *
 * @category  indexer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
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
     */
    public function index($directory);
}