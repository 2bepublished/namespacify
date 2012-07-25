<?php

/**
 * ParserInterface
 *
 * @category  parser
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Parser;

use Pub\Namespacify\Index\IndexInterface;

/**
 * ParserInterface
 *
 * @category  parser
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
interface ParserInterface
{
    /**
     * Parses the files from the given index and extracts the source code of
     * the classes.
     *
     * @param \Pub\Namespacify\Index\IndexInterface
     *
     * @return \Pub\Namespacify\Index\ParsedIndex
     */
    public function parse(IndexInterface $index);
}
