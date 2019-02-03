<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReleaseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('release')
            ->addArgument(
                'Release version',
                InputArgument::REQUIRED,
                '<version>'
            )
            ->addOption(
                'config',
                'c',
                InputOption::VALUE_OPTIONAL,
                'config file path',
                './.cra.yml'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO
    }
}
