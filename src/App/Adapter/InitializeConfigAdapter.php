<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Sasamium\Cra\App\GitService;
use Sasamium\Cra\Core\Port\InitializeConfigPort;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * InitializeConfigPortの実装
 */
class InitializeConfigAdapter implements InitializeConfigPort
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var QuestionHelper
     */
    private $questionHelper;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param QuestionHelper  $questionHelper
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $questionHelper;
    }

    /**
     * {@inheritDoc}
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * {@inheritDoc}
     */
    public function questionGitService(): GitService
    {
        // TODO: 選択させる
        return GitService::GITHUB();
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $configPath, array $config): void
    {
        file_put_contents($configPath, Yaml::dump($config));
    }
}
