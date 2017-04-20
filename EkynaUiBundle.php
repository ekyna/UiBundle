<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle;

use Ekyna\Bundle\UiBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EkynaUiBundle
 * @package Ekyna\Bundle\UiBundle
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUiBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new Compiler\FormJsPass());
        $container->addCompilerPass(new Compiler\ActionAutoConfigurePass());
    }
}
