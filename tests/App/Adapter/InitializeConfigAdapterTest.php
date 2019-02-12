<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Cz\Git\IGit;
use Howyi\Evi;
use Mockery as M;
use PHPUnit\Framework\TestCase;
use Sasamium\Cra\App\GitService;
use Sasamium\Cra\Core\ReleaseBranch;
use Sasamium\Cra\Core\SortedVersionList;
use Sasamium\Cra\Core\Version;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class InitializeConfigAdapterTest extends TestCase
{
    /**
     * @var string
     */
    private const CONFIG_PATH = 'put.yml';

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
     * @var InitializeConfigAdapter
     */
    private $subject;

    public function setup()
    {
        $this->input = M::mock(InputInterface::class);
        $this->output = M::mock(OutputInterface::class);
        $this->questionHelper = M::mock(QuestionHelper::class);
        $this->subject = new InitializeConfigAdapter(
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

    public function testPut()
    {
        $config = ['sasami' => 'ume'];
        $this->subject->put(self::CONFIG_PATH, $config);
        self::assertSame($config, Evi::parse(self::CONFIG_PATH));
    }

    /**
     * @depends testPut
     */
    public function testExist()
    {
        self::assertTrue($this->subject->exists(self::CONFIG_PATH));
        unlink(self::CONFIG_PATH);
        self::assertFalse($this->subject->exists(self::CONFIG_PATH));
    }

    public function testQuestionGitService()
    {
        $gitService = GitService::GITHUB();
        $this->questionHelper->shouldReceive('ask')
            ->with(
                $this->input,
                $this->output,
                M::type(ChoiceQuestion::class)
            )->andReturn($gitService->value());
        self::assertSame($gitService, $this->subject->questionGitService());
    }
}
