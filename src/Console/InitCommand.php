<?php

namespace Howyi\Cra\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected function configure()
    {
        $this->setName('init');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO
    }
}
