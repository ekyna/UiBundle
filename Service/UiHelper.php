<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service;

use Ekyna\Bundle\UiBundle\Model\FAIcons;

/**
 * Class UiHelper
 * @package Ekyna\Bundle\UiBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UiHelper
{
    /**
     * Wraps the given value into a clickable clipboard copy element.
     *
     * @param string|null $value
     * @param array       $options
     * @return string
     *
     * @noinspection PhpUnused
     */
    public static function renderClipboardCopy(?string $value, array $options = []): string
    {
        if (empty($value)) {
            return '';
        }

        $tag = $options['tag'] ?? 'span';
        $label = $options['label'] ?? $value;

        return sprintf('<%s data-clipboard-copy="%s">%s</%s>', $tag, $value, $label, $tag);
    }

    /**
     * Renders a font awesome icon.
     *
     * @param string|null $icon
     * @param string|null $classes
     * @return string
     *
     * @noinspection PhpUnused
     */
    public static function renderFaIcon(string $icon = null, string $classes = null): string
    {
        if (is_null($icon) || !FAIcons::isValid($icon, false)) {
            return '';
        }

        return sprintf('<i class="fa fa-%s %s"></i>', $icon, $classes);
    }
}
