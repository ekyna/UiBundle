<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\DependencyInjection\Compiler;

use Ekyna\Bundle\UiBundle\Action\FlashTrait;
use Ekyna\Component\Resource\Bridge\Symfony\DependencyInjection\Compiler\ActionAutoConfigurePass as BasePass;

/**
 * Class ActionAutoConfigurePass
 * @package Ekyna\Bundle\UiBundle\DependencyInjection\Compiler
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ActionAutoConfigurePass extends BasePass
{
    protected function getAutoconfigureMap(): array
    {
        return [
            FlashTrait::class => [
                'setFlashHelper' => 'ekyna_ui.helper.flash',
            ],
        ];
    }
}
