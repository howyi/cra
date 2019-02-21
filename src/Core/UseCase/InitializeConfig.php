<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Sasamium\Cra\Core\Port\QuestionPort;
use Sasamium\Cra\Core\Port\StoragePort;

/**
 * Configを新規作成する
 */
class InitializeConfig
{
    /**
     * @var StoragePort
     */
    private $storage;

    /**
     * @var QuestionPort
     */
    private $question;

    /**
     * @param StoragePort  $storage
     * @param QuestionPort $question
     */
    public function __construct(
        StoragePort $storage,
        QuestionPort $question
    ) {
        $this->storage = $storage;
        $this->question = $question;
    }

    /**
     * @param string $configPath
     * @throws \RuntimeException
     */
    public function run(string $configPath): void
    {
        if ($this->storage->exists($configPath)) {
            throw new \RuntimeException('File already exists: ' . $configPath);
        }

        $config = [];

        $gitServiceDefaultConfig = [
            'github' => [
                'TOKEN' => 'env:GITHUB_TOKEN',
            ],
            'gitlab' => [
                'TOKEN' => 'env:GITLAB_TOKEN',
            ],
        ];

        $answer = $this->question->select(
            'Please select Git Service.',
            array_keys($gitServiceDefaultConfig)
        );

        $config['git_service'] = [
            'name'    => $answer,
            'setting' => $gitServiceDefaultConfig[$answer],
        ];

        // TODO: その他

        $this->storage->putFromArray($configPath, $config);
    }
}
