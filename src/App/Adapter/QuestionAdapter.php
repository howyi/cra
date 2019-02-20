<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Sasamium\Cra\Core\Port\QuestionPort;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * QuestionAdapter
 */
class QuestionAdapter implements QuestionPort
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
     * {@inheritdoc}
     */
    public function select(string $question, array $choices)
    {
        $choiceQuestion = new ChoiceQuestion($question, $choices);
        $choiceQuestion->setErrorMessage('%s is invalid.');
        return $this->questionHelper->ask(
            $this->input,
            $this->output,
            $choiceQuestion
        );
    }
}
