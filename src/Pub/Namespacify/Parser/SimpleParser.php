<?php

/**
 * SimpleParser
 *
 * @category  parser
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
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
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */
class SimpleParser implements ParserInterface
{
    /** @var string Pattern to match the code of a single class */
    protected $classCodePattern = '/^(class (.*)$(.*)^})/msU';

    /** @var string Pattern to match name of class */
    protected $classNamePattern = '/class (.*)(\s|\{)/sU';

    /** {@inheritDoc} */
    public function parse(IndexInterface $index)
    {
        $parsedIndex = new ParsedIndex();

        // Iterate through all items (= files)
        foreach ($index->getAll() as $item) {
            // Build namespace based on path and filename.
            $namespace = str_replace('/', '\\', substr($item['file']->getRelativePathname(), 0, -4));

            $content = $item['file']->getContents();

            // Extract all classes from the files source code
            if (preg_match_all($this->classCodePattern, $content, $matches)) {
                // Iterate through all matches (=classes)
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $className = $this->parseClassName($matches[1][$i]);
                    if ($className) {
                        // Add the class (+ namespace + code) to the index
                        $parsedIndex->add(array(
                            'class'     => $className,
                            'namespace' => $namespace,
                            'code'      => $matches[1][$i]
                        ));
                    }
                }
            }
        }

        return $parsedIndex;
    }

    /**
     * Extracts the name of the class from the code of the class.
     *
     * @param string $code The code of the class
     *
     * @return string The name of the class or FALSE if the name could not be parsed.
     */
    protected function parseClassName($code)
    {
        preg_match($this->classNamePattern, $code, $matches);
        if (isset($matches[1]) && $matches[1]) {
            return $matches[1];
        }
        return false;
    }
}
