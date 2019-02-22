<?php declare(strict_types=1);

namespace Sasamium\Cra\Core\Port;

/**
 * QuestionPort
 */
interface QuestionPort
{
    /**
     * @param string $question
     * @param array  $choices
     * @return mixed
     */
    public function select(string $question, array $choices);
}
