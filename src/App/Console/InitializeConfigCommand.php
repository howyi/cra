<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Console;

use Sasamium\Cra\App\Adapter\InitializeConfigAdapter;
use Sasamium\Cra\Config;
use Sasamium\Cra\Core\UseCase\InitializeConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeConfigCommand extends Command
{
    protected function configure()
    {
        $this->setName('init');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configPath = getcwd() . DIRECTORY_SEPARATOR . Config::DEFAULT_PATH;

        $adapter = new InitializeConfigAdapter(
            $input,
            $output,
            $this->getHelper('question')
        );
        $useCase = new InitializeConfig($adapter);
        $useCase->run($configPath);
    }
}
