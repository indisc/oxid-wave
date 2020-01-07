<?php
namespace Command\SaCommands\;


use OxidEsales\Eshop\Core\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Tests\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 *  Class Example Commands
 * @package oxruncmds\Command\SaCommands\
 */

class ExampleCommand extends Command
{
    /**
     * Configures the current Command.
     */
    protected function configure()
    {
        $this
            ->setName('example:hello')
            ->setDescription('Example command')
            ->addOption('shopId', null, InputOption::VALUE_OPTIONAL, null);
    }
    /**
     * Executes the current command.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Hello, Example</info>");
    }
    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getApplication()->bootstrapOxid();
    }
}

?>