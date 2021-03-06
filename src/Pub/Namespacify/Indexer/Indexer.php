<?php

/**
 * Indexer
 *
 * @category  indexer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */

namespace Pub\Namespacify\Indexer;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

use Pub\Namespacify\Exception\NamespaceExistsException;
use Pub\Namespacify\Index\IndexInterface;

/**
 * Indexer
 *
 * @category  indexer
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */
class Indexer implements IndexerInterface
{
    /** @var \Pub\Namespacify\Index\IndexInterface */
    private $index;

    /** @var \Symonfy\Component\Finder\Finder */
    private $finder;

    /** @var string Pattern to match class name */
    private $classMatchPattern = '/class ([A-Z][a-zA-Z0-9_]+)/';

    /** @var string Pattern to match namespaces */
    private $namespaceMatchPattern = '/^namespace\s+([a-zA-Z0-9_\\\\]+)\s*;$/m';

    /**
     * Sets the indexer.
     *
     * @param \Pub\Namespacify\Index\IndexInterface The indexer
     *
     * @return \Pub\Namespacify\Indexer\Indexer self
     */
    public function setIndex(IndexInterface $index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * Returns the index.
     *
     * @return Pub\Namespacify\Index\Index The index
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Sets the finder.
     *
     * @param \Symonfy\Component\Finder\Finder $finder The finder
     *
     * @return \Pub\Namespacify\Indexer\Indexer self
     */
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;
        return $this;
    }

    /**
     * Returns the finder.
     *
     * @return Symfony\Component\Finder\Finder The finder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /** {@inheritdoc} */
    public function index($directory, $exclude = null)
    {
        $iterator = $this->getFileIterator($directory, $exclude);
        foreach ($iterator as $file) {
            $content = $file->getContents();
            if (preg_match($this->namespaceMatchPattern, $content, $matches)) {
                throw new NamespaceExistsException(sprintf(
                    'Found namespace "%s" in file "%s"',
                    isset($matches[1]) ? $matches[1] : '[UNKOWN]',
                    $file->getRelativePathName()
                ));
            }
            if (preg_match_all($this->classMatchPattern, $content, $matches)) {
                $this->index->add(array(
                    'file'      => $file,
                    'classes'   => $matches[1]
                ));
            }
        }
        return $this->index;
    }

    /**
     * Returns an iterator that points to all files that should be added to the index.
     *
     * @param string $directory The directory
     *
     * @return \Iterator An iterator
     *
     * @codeCoverageIgnore
     */
    protected function getFileIterator($directory, $exclude = null)
    {
        $finder = $this->finder
            ->files()
            ->name('*.php')
            ->depth('>= 0');
        if ($exclude) {
            $finder->filter(function (SplFileInfo $file) use ($exclude) {
                if (preg_match('/'.$exclude.'/', $file->getRelativePathName())) {
                    return false;
                } else {
                    return true;
                }
            });
        }
        return $finder
            ->in($directory)
        ;
    }
}