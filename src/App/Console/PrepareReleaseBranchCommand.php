<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Console;

use Cz\Git\GitRepository;
use Sasamium\Cra\App\Adapter\ConfigAdapter;
use Sasamium\Cra\App\Adapter\GitAdapter;
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
        $configAdapter = new ConfigAdapter($configPath);

        $gitAdapter = new GitAdapter(new GitRepository(getcwd()));
        $useCase = new PrepareReleaseBranch($gitAdapter, $configAdapter);

        // configureでREQUIREDしているため、必ずstringが返る
        /** @var string $releaseTypeOrVersion */
        $releaseTypeOrVersion = $input->getArgument('New version');

        /** @var ReleaseType|null $releaseType */
        $releaseType = ReleaseType::memberByValueWithDefault($releaseTypeOrVersion, null);
        if (is_null($releaseType) === false) {
            $useCase->byReleaseType($releaseType);
            return;
        }

        if (Version::isValidString($releaseTypeOrVersion)) {
            $version = Version::wipFromString($releaseTypeOrVersion);
            $useCase->byVersion($version);
            return;
        }

        throw new \RuntimeException(sprintf('不正なリリースタイプ、もしくはバージョン番号を渡された: %s', $releaseTypeOrVersion));
    }
}
