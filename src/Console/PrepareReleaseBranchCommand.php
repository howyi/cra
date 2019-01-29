<?php

namespace Howyi\Cra\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrepareReleaseBranchCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('prepare:release-branch')
            ->addArgument(
                'New version',
                InputArgument::REQUIRED,
                '<major|minor|patch|new-version>'
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
