<?php declare(strict_types=1);

namespace Sasamium\Cra\App\Adapter;

use Cz\Git\IGit;
use Sasamium\Cra\Core\Port\GitPort;

/**
 * GitAdapter
 */
class GitAdapter implements GitPort
{
    /**
     * @var IGit
     */
    private $git;

    /**
     * @param IGit $git
     */
    public function __construct(IGit $git)
    {
        $this->git = $git;
    }

    /**
     * {@inheritDoc}
     */
    public function listUpTags(): array
    {
        return $this->git->getTags() ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function checkout(string $name): void
    {
        $this->git->checkout($name);
    }

    /**
     * {@inheritDoc}
     */
    public function createBranch(string $name): void
    {
        $this->git->createBranch($name, /** afterCheckout = */ true);
    }
}
