<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Console;

use Cz\Git\GitRepository;
use Sasamium\Cra\App\Adapter\PrepareReleaseBranchAdapter;
use Sasamium\Cra\Config;
use Sasamium\Cra\Core\ReleaseType;
use Sasamium\Cra\Core\UseCase\PrepareReleaseBranch;
use Sasamium\Cra\Core\Version;
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
        // configureでデフォルト値を指定しているため、必ずstringが返る
        /** @var string $configPath */
        $configPath = $input->getOption('config');
        Config::set($configPath);

        // configureでREQUIREDしているため、必ずstringが返る
        /** @var string $releaseTypeOrVersion */
        $releaseTypeOrVersion = $input->getArgument('New version');
        $releaseType = ReleaseType::memberByValueWithDefault($releaseTypeOrVersion, null);
        if (is_null($releaseType)) {
            if (Version::isValidString($releaseTypeOrVersion)) {
                throw new \RuntimeException('バージョン指定での準備はまだサポートされていない');
            }
            throw new \RuntimeException('不正なリリースタイプを渡された');
        }

        $adapter = new PrepareReleaseBranchAdapter(
            new GitRepository(getcwd()),
            Config::releaseBranchPrefix(),
            Config::versionPrefix(),
            Config::masterBranch()
        );
        $useCase = new PrepareReleaseBranch($adapter);

        $useCase->run($releaseType);
    }
}
