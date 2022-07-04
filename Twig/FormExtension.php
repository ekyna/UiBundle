<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Twig;

use Ekyna\Bundle\UiBundle\Service\BootstrapHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class FormExtension
 * @package Ekyna\Bundle\UiBundle\Twig
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FormExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        $nodeOptions = [
            'is_safe'    => ['html'],
            'node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode',
        ];

        return [
            new TwigFunction(
                'bootstrap_set_style',
                [BootstrapHelper::class, 'setStyle']
            ),
            new TwigFunction(
                'bootstrap_get_style',
                [BootstrapHelper::class, 'getStyle']
            ),
            new TwigFunction(
                'bootstrap_set_sizing',
                [BootstrapHelper::class, 'setSizing']
            ),
            new TwigFunction(
                'bootstrap_get_sizing',
                [BootstrapHelper::class, 'getSizing']
            ),
            new TwigFunction(
                'bootstrap_set_col_size',
                [BootstrapHelper::class, 'setColSize']
            ),
            new TwigFunction(
                'bootstrap_get_col_size',
                [BootstrapHelper::class, 'getColSize']
            ),
            new TwigFunction(
                'bootstrap_set_widget_col',
                [BootstrapHelper::class, 'setWidgetCol']
            ),
            new TwigFunction(
                'bootstrap_get_widget_col',
                [BootstrapHelper::class, 'getWidgetCol']
            ),
            new TwigFunction(
                'bootstrap_set_label_col',
                [BootstrapHelper::class, 'setLabelCol']
            ),
            new TwigFunction(
                'bootstrap_get_label_col',
                [BootstrapHelper::class, 'getLabelCol']
            ),
            new TwigFunction(
                'bootstrap_set_simple_col',
                [BootstrapHelper::class, 'setSimpleCol']
            ),
            new TwigFunction(
                'bootstrap_get_simple_col',
                [BootstrapHelper::class, 'getSimpleCol']
            ),
            new TwigFunction(
                'bootstrap_backup_form_settings',
                [BootstrapHelper::class, 'backupFormSettings']
            ),
            new TwigFunction(
                'bootstrap_restore_form_settings',
                [BootstrapHelper::class, 'restoreFormSettings']
            ),
            new TwigFunction(
                'form_help',
                null,
                $nodeOptions
            ),
            new TwigFunction(
                'checkbox_row',
                null,
                $nodeOptions
            ),
            new TwigFunction(
                'radio_row',
                null,
                $nodeOptions
            ),
            new TwigFunction(
                'global_form_errors',
                null,
                $nodeOptions
            ),
            new TwigFunction(
                'form_control_static',
                [BootstrapHelper::class, 'formControlStaticFunction'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'icon',
                [BootstrapHelper::class, 'iconFunction'],
                ['pre_escape' => 'html', 'is_safe' => ['html']]
            ),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'parse_icons',
                [BootstrapHelper::class, 'parseIconsFilter'],
                ['pre_escape' => 'html', 'is_safe' => ['html']]
            ),
        ];
    }
}
