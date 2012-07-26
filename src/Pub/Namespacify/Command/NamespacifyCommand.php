<?php

/**
 * NamespacifyCommand
 *
 * @category  command
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */

namespace Pub\Namespacify\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * NamespacifyCommand
 *
 * @category  command
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class NamespacifyCommand extends Command implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * Sets the Container associated with this Controller.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('go')
            ->setDescription('Adds namespaces to the classes in the given directory.')
            ->addArgument('dir', InputArgument::REQUIRED, 'Directory name')
            ->addArgument('outputDir', InputArgument::REQUIRED, 'Output directory name')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        $outputDir = $input->getArgument('outputDir');

        $indexer = $this->container->get('indexer');
        $index = $indexer->index($dir);

        $parser = $this->container->get('parser');
        $parsedIndex = $parser->parse($index);

        $generator = $this->container->get('generator');
        $generator->setLoggingCallback(function ($namespace, $class, $file) use ($output)
        {
            $output->writeln(sprintf('%s\\%s --> %s', $namespace, $class, $file ));
        });
        $generator->generate($parsedIndex, $outputDir);

        $output->writeln("Directory: " . $dir);
    }
}
