<?php

/**
 * Psr0Generator
 *
 * @category  generator
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */

namespace Pub\Namespacify\Generator;

use Symfony\Component\Filesystem\Filesystem;

use Pub\Namespacify\Index\ParsedIndex;
use Pub\Namespacify\Transformer\TransformerInterface;

/**
 * Psr0Generator
 *
 * @category  generator
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
 */
class Psr0Generator implements GeneratorInterface
{
    /** @var Symfony\Component\Filesystem\Filesystem */
    protected $filesystem;

    /** @var callback */
    protected $loggingCallback;

    /** @var Pub\Namespacify\Transformer\TransformerInterface */
    protected $transformer;

    /** @var \Closure */
    protected $writer;

    /** @var string RegEx pattern to find class usages (new and with static :: operator) */
    private $classUsagePattern;

    public function __construct()
    {
        $this->classUsagePattern =
            '/' .
            '(new (([a-zA-Z0-9-]+))|' .
            '([a-zA-Z0-9_]+)::)|' .
            '(extends\s+([a-zA-Z0-9_]+))|' .
            '(\(\s*([a-zA-Z0-9_]+)\s\$[a-zA-Z0-9_]+)|' .
            '(,\s*([a-zA-Z0-9_]+)\s\$)' .
            '/'
        ;
    }

    /**
     * Sets the file system.
     *
     * @param Symfony\Component\Filesystem\Filesystem $filesystem Filesystem
     *
     * @return Pub\Namespacify\Generator\Psr0Generator
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    /**
     * Returns the Filesystem.
     *
     * @return Symfony\Component\Filesystem\Filesystem The Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Sets the logging callback function.
     *
     * @param \Closure $loggingCallback The logging callback
     *
     * @return \Pub\Namespacify\Generator\Psr0Generator
     */
    public function setLoggingCallback(\Closure $loggingCallback)
    {
        $this->loggingCallback = $loggingCallback;
        return $this;
    }

    /**
     * Sets the writer.
     *
     * Writer is the function that writes to the file system.
     *
     * @param \Closure $writer The writer closure
     *
     * @return \Pub\Namespacify\Generator\Psr0Generator
     */
    public function setWriter(\Closure $writer)
    {
        $this->writer = $writer;
        return $this;
    }

    /**
     * Returns the writer. If no writer is specified, returns a default writer.
     *
     * @return \Closure The writer clojure
     */
    public function getWriter()
    {
        if (!$this->writer) {
            // @codeCoverageIgnoreStart
            return function ($file, $code) {
                file_put_contents($file, $code);
            };
            // @codeCoverageIgnoreEnd
        }
        return $this->writer;
    }

    /**
     * Sets the transformer.
     *
     * @param \Pub\Namespacify\Generator\Psr0Generator $transformer The transformer
     *
     * @return \Pub\Namespacify\Generator\Psr0Generator
     */
    public function setTransformer(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    /** {@inheritDoc} */
    public function generate(ParsedIndex $index, $outputDir, $namespacePrefix = null, $transformerCallback = null)
    {
        // Remove trailing slash
        $outputDir = preg_replace('/\/$/', '', $outputDir);

        foreach ($index->getAll() as $class) {
            // Add namespace prefix to namespace
            if ($namespacePrefix) {
                $class = $this->addNamespacePrefix($class, $namespacePrefix);
            }

            // Search which classes are used in this class and "use" statements for these classes
            $useStatements = $this->generateUseStatements($index, $class['code'], $namespacePrefix);

            // Generate the code for the file
            $codeTemplate = "<?php\n\nnamespace %s;\n%s\n%s\n";
            $class['code'] = sprintf($codeTemplate, $class['namespace'], $useStatements, $class['code']);

            // Apply the transformer
            if ($this->transformer) {
                $class = $this->transformer->transform($class);
            }

            // Apply the project specific code transformer
            if ($transformerCallback) {
                $callbackWrapper = function () use ($transformerCallback, $class, $index) {
                    require_once $transformerCallback;
                    return call_user_func(array('\\CodeTransformerCallback', 'transform'), $class, $index->getAll());
                };
                $class = call_user_func($callbackWrapper);
            }

            // Generate the directory (if required)
            $dir = $outputDir . '/' . str_replace('\\', '/', $class['namespace']);
            $this->filesystem->mkdir($dir);
            $file = $dir . '/' . $class['class'] . '.php';
            call_user_func($this->getWriter(), $file, $class['code']);

            // Loggging
            if ($this->loggingCallback) {
                call_user_func($this->loggingCallback, $class['namespace'], $class['class'], $file);
            }
        }

        return $this;
    }

    /**
     * Generates the required "use" statements based on the given code and index.
     *
     * @param \Pub\Namespacify\Index\ParsedIndex $index           The parsed index
     * @param string                             $code            The code
     * @param string                             $namespacePrefix The namespace prefix; defaults to NULL
     *
     * @return string The "use" statements
     */
    protected function generateUseStatements(ParsedIndex $index, $code, $namespacePrefix = null)
    {
        $useStatementTemplate = "use %s\\%s;\n";

        $useStatements = '';
        // Match alles uses of classes in the code
        if (preg_match_all($this->classUsagePattern, $code, $matches)) {
            // Merge uses of new and :: and remove duplicates
            $matches = array_unique(array_merge(
                $matches[3], // new UsedClass
                $matches[4], // UsedClass::
                $matches[6], // extends UsedClass
                $matches[8], // function method(UsedClass $var)
                $matches[10] // function method($var, UsedClass $var)
            ));
            foreach ($matches as $match) {
                $match = trim($match);
                // Regular expressions also matches array types defined in parameter definition of a method
                // Skip them!
                if ('array' === $match) {
                    continue;
                }
                // Don't bother with empty class names (probably extracted from PHPDoc) and parent and self uses
                if (strlen($match) > 0 && 'parent' !== $match && 'self' !== $match) {
                    // If the class name is in the index, use the class and optionally add the namespace prefix.
                    // If the class is not in the index, this is probably a Standard PHP class from the
                    // "" (empty) namespace. To make it work, we add a use statement with "\ClassName"
                    if ($index->has($match)) {
                        $class = $index->get($match);
                        if ($namespacePrefix) {
                            $class = $this->addNamespacePrefix($class, $namespacePrefix);
                        }
                    } else {
                        $class = array(
                            'namespace' => '',
                            'class'     => $match . ' as ' . $match
                        );
                    }
                    $useStatements .= sprintf($useStatementTemplate, $class['namespace'], $class['class']);
                }
            }
        }

        return strlen($useStatements) > 0 ? "\n" . $useStatements : "";
    }

    /**
     * Adds the namespace prefix to the class array.
     *
     * @param array  $class           The class array
     * @param string $namespacePrefix The namespace prefix
     *
     * @return array The class array with prefixed namespace
     */
    protected function addNamespacePrefix($class, $namespacePrefix)
    {
        $class['namespace'] = $namespacePrefix .
                    (strlen($class['namespace']) ? '\\' . $class['namespace'] : '');
        return $class;
    }
}