<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class QuestionAdapterTest extends TestCase
{
    /**
     * @var M\MockInterface
     */
    private $input;

    /**
     * @var M\MockInterface
     */
    private $output;

    /**
     * @var M\MockInterface
     */
    private $questionHelper;

    /**
     * @var QuestionAdapter
     */
    private $adapter;

    public function setup()
    {
        $this->input = M::mock(InputInterface::class);
        $this->output = M::mock(OutputInterface::class);
        $this->questionHelper = M::mock(QuestionHelper::class);
        $this->adapter = new QuestionAdapter(
            $this->input,
            $this->output,
            $this->questionHelper
        );
    }

    public function teardown()
    {
        /** @see https://github.com/mockery/mockery/issues/376 */
        if ($container = M::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        M::close();
    }

    public function testSelect(): void
    {
        $this->questionHelper->shouldReceive('ask')
            ->with(
                $this->input,
                $this->output,
                M::type(ChoiceQuestion::class)
            );
        $this->adapter->select('spicy', ['chicken', 'wing']);
    }
}
