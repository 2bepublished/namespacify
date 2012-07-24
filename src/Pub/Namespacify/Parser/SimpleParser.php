<?php

/**
 * SimpleParser
 *
 * PHP Version 5.3.10
 *
 * @category  parser
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Parser;

use Pub\Namespacify\Index\IndexInterface;
use Pub\Namespacify\Index\ParsedIndex;

/**
 * SimpleParser
 *
 * @category  parser
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class SimpleParser implements ParserInterface
{
    /** @var string Pattern to match the code of a single class */
    protected $classCodePattern
        = '/^(class ([A-Z][a-zA-Z0-9_]+)\s.+?$.*^})/msU';

    /** {@inheritDoc} */
    public function parse(IndexInterface $index)
    {
        $parsedIndex = new ParsedIndex();

        foreach ($index->getAll() as $item) {
            $namespace = str_replace(
                '/',
                '\\',
                $item['file']->getRelativePath()
            );
            $content = $item['file']->getContents();
            if (preg_match_all($this->classCodePattern, $content, $matches)) {
                for ($i=0; $i < count($matches[0]); $i++) {
                    $parsedIndex->add(array(
                        'class'     => $matches[2][$i],
                        'namespace' => $namespace,
                        'code'      => $matches[1][$i]
                    ));
                }
            }
        }

        return $parsedIndex;
    }
}
