<?php

/**
 * NamespacifyCommand
 *
 * @category  command
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 * @link      http://www.2bepublished.at 2bePUBLISHED
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
 * @link      http://www.2bepublished.at 2bePUBLISHED
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
            ->addOption('prefix', null, InputOption::VALUE_REQUIRED, 'Namespace prefix')
            ->addOption('exclude', null, InputOption::VALUE_REQUIRED, 'Exclude files that match the RegEx')
            ->addOption('transformer', null, InputOption::VALUE_REQUIRED, 'Project specific code transformer')
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
        $index = $indexer->index($dir, $input->getOption('exclude'));

        $output->writeln(sprintf("Indexed %d files.", count($index->getAll())));

        $parser = $this->container->get('parser');
        $parsedIndex = $parser->parse($index);

        $output->writeln(sprintf("Parsed %d classes.", count($parsedIndex->getAll())));

        $generatorCount = 0;
        $generator = $this->container->get('generator');
        $generator->setLoggingCallback(function ($namespace, $class, $file) use ($input, $output, &$generatorCount)
        {
            if ($input->getOption('verbose')) {
                $output->writeln(sprintf('%s\\%s --> %s', $namespace, $class, $file));
            }
            $generatorCount++;
        });
        $generator->generate($parsedIndex, $outputDir, $input->getOption('prefix'), $input->getOption('transformer'));

        $output->writeln(sprintf("Generated %d classes.", $generatorCount));
    }
}
