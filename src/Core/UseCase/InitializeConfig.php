<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Sasamium\Cra\Core\Port\DefaultConfigPort;
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
     * @var DefaultConfigPort
     */
    private $defaultConfig;

    /**
     * @param StoragePort       $storage
     * @param QuestionPort      $question
     * @param DefaultConfigPort $defaultConfig
     */
    public function __construct(
        StoragePort $storage,
        QuestionPort $question,
        DefaultConfigPort $defaultConfig
    ) {
        $this->storage = $storage;
        $this->question = $question;
        $this->defaultConfig = $defaultConfig;
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

        $gitServiceDefaultConfig = $this->defaultConfig->gitServices();

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

        // TODO: ファイルが作成できたよ、みたいな通知
    }
}
