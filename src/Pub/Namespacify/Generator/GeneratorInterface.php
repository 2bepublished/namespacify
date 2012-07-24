<?php

/**
 * GeneratorInterface
 *
 * PHP Version 5.3.10
 *
 * @category  generator
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Generator;

use Pub\Namespacify\Index\ParsedIndex;

/**
 * GeneratorInterface
 *
 * @category  generator
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
interface GeneratorInterface
{
    /**
     * Generates the class files for the the index in the given directory.
     *
     * @param \Pub\Namespacify\Index\ParsedIndex $index     The parsed index
     * @param string                             $directory The directory
     *
     * @return \Pub\Namespacify\Generator\GeneratorInterface
     */
    public function generate(ParsedIndex $index, $directory);
}
