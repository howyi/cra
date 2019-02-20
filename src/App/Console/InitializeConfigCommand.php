<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Console;

use Sasamium\Cra\App\Adapter\ConfigAdapter;
use Sasamium\Cra\App\Adapter\QuestionAdapter;
use Sasamium\Cra\App\Adapter\Storage\FilesystemAdapter;
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
        $configPath = getcwd() . DIRECTORY_SEPARATOR . ConfigAdapter::DEFAULT_PATH;

        // output
        $useCase = new InitializeConfig(
            new ConfigAdapter(),
            new FilesystemAdapter(),
            new QuestionAdapter(
                $input,
                $output,
                $this->getHelper('question')
            )
        );
        $useCase->run($configPath);
    }
}
