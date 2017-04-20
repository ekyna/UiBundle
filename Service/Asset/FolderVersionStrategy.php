<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service\Asset;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

use function ltrim;
use function sprintf;

/**
 * Class FolderVersionStrategy
 * @package Ekyna\Bundle\CoreBundle\Service\Asset
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FolderVersionStrategy implements VersionStrategyInterface
{
    private string $version;


    /**
     * Constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->version = $version;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(string $path): string
    {
        return $this->version;
    }

    /**
     * @inheritDoc
     */
    public function applyVersion(string $path): string
    {
        if ($path && '/' === $path[0]) {
            return $path;
        }

        return sprintf('%s/%s', $this->getVersion($path), ltrim($path, '/'));
    }
}
