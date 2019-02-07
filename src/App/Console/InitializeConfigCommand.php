<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class InitializeConfigCommand extends Command
{
    protected function configure()
    {
        $this->setName('init');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $itemCallable = function (CliMenu $menu) {
            echo $menu->getSelectedItem()->getText();
        };

        $menu = (new CliMenuBuilder())
            ->setTitle('cra Configure')
            ->addItem('Initialize', $itemCallable)
            ->addItem('Change', $itemCallable)
            ->addLineBreak('-')
            ->setBorder(1, 2, 'green')
            ->setPadding(2, 4)
            ->setMarginAuto()
            ->build();

        $menu->open();
    }
}
