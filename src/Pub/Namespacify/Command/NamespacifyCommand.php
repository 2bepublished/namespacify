<?php

/**
 * NamespacifyCommand
 *
 * PHP Version 5.3.10
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

/**
 * NamespacifyCommand
 *
 * @category  command
 * @package   namespacify
 * @author    Florian Eckerstorfer <florian@theroadtojoy.at>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @copyright 2012 2bePUBLISHED Internet Services Austria GmbH
 */
class NamespacifyCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('namespacify')
            ->setDescription(
                'Adds namespaces to the classes in the given directory.'
            )
            ->addArgument('dir', InputArgument::REQUIRED, 'Directory name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        $output->writeln("Directory: " . $dir);
    }
}
