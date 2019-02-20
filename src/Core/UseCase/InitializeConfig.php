<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\UseCase;

use Sasamium\Cra\Core\Port\ConfigPort;
use Sasamium\Cra\Core\Port\QuestionPort;
use Sasamium\Cra\Core\Port\StoragePort;

/**
 * Configを新規作成する
 */
class InitializeConfig
{
    /**
     * @var ConfigPort
     */
    private $config;
    
    /**
     * @var StoragePort
     */
    private $storage;
    
    /**
     * @var QuestionPort
     */
    private $question;

    /**
     * @param ConfigPort   $config
     * @param StoragePort  $storage
     * @param QuestionPort $question
     */
    public function __construct(
        ConfigPort $config,
        StoragePort $storage,
        QuestionPort $question
    ) {
        $this->config = $config;
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
        
        $gitServicePorts = $this->config->supportedGitServicePorts();
        
        $choices = [];
        foreach ($gitServicePorts as $port) {
            $choices[$port->name()] = $port;
        }
        $answer = $this->question->select(
            'Please select Git Service.',
            array_keys($choices)
        );
        $gitServicePort = $choices[$answer];
        $config['service'][$gitServicePort->name()] = $gitServicePort->defaultConfig();

        // TODO: その他
        
        $this->storage->putFromArray($configPath, $config);
    }
}
