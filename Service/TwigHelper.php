<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service;

use Twig\Loader\LoaderInterface;

use function preg_match;

/**
 * Class TwigHelper
 * @package Ekyna\Bundle\UiBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TwigHelper
{
    public function __construct(
        private readonly LoaderInterface $loader
    ) {
    }

    public function templateExists(string $name): bool
    {
        if (!preg_match('~^@?[A-Za-z0-9-_]+(/[A-Za-z0-9-_]+)*\.html\.twig+$~', $name)) {
            return false;
        }

        return $this->loader->exists($name);
    }
}
