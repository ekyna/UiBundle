<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service;

use RuntimeException;

use function array_pop;
use function array_push;
use function count;
use function json_encode;
use function json_last_error_msg;
use function preg_replace_callback;
use function sprintf;
use function str_replace;

use const JSON_FORCE_OBJECT;
use const JSON_HEX_APOS;
use const JSON_PRESERVE_ZERO_FRACTION;

/**
 * Class BootstrapHelper
 * @package Ekyna\Bundle\UiBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class BootstrapHelper
{
    private string $style         = 'horizontal';
    private string $sizing        = 'md';
    private string $colSize       = 'md';
    private int    $widgetCol     = 10;
    private int    $labelCol      = 2;
    private int    $simpleCol     = 0;
    private array  $settingsStack = [];
    private string $iconPrefix;
    private string $iconTag;

    public function __construct(string $iconPrefix = 'glyphicon', string $iconTag = 'span')
    {
        $this->iconPrefix = $iconPrefix;
        $this->iconTag = $iconTag;
    }

    /**
     * Sets the style.
     *
     * @param string $style Name of the style
     */
    public function setStyle(string $style): void
    {
        $this->style = $style;
    }

    /**
     * Returns the style.
     *
     * @return string Name of the style
     */
    public function getStyle(): string
    {
        return $this->style;
    }

    /**
     * Sets the field and label sizing.
     *
     * @param string $sizing Field sizing (sm, md or lg)
     */
    public function setSizing(string $sizing): void
    {
        $this->sizing = $sizing;
    }

    /**
     * Returns the field and label sizing.
     *
     * @return string Field sizing (sm, md or lg)
     */
    public function getSizing(): string
    {
        return $this->sizing;
    }

    /**
     * Sets the column size.
     *
     * @param string $colSize Column size (xs, sm, md or lg)
     */
    public function setColSize(string $colSize): void
    {
        $this->colSize = $colSize;
    }

    /**
     * Returns the column size.
     *
     * @return string Column size (xs, sm, md or lg)
     */
    public function getColSize(): string
    {
        return $this->colSize;
    }

    /**
     * Sets the number of columns of widgets.
     *
     * @param int $widgetCol Number of columns.
     */
    public function setWidgetCol(int $widgetCol): void
    {
        $this->widgetCol = $widgetCol;
    }

    /**
     * Returns the number of columns of widgets.
     *
     * @return int Number of columns.Class
     */
    public function getWidgetCol(): int
    {
        return $this->widgetCol;
    }

    /**
     * Sets the number of columns of labels.
     *
     * @param int $labelCol Number of columns.
     */
    public function setLabelCol(int $labelCol): void
    {
        $this->labelCol = $labelCol;
    }

    /**
     * Returns the number of columns of labels.
     *
     * @return int Number of columns.
     */
    public function getLabelCol(): int
    {
        return $this->labelCol;
    }

    /**
     * Sets the number of columns of simple widgets.
     *
     * @param int $simpleCol Number of columns.
     */
    public function setSimpleCol(int $simpleCol): void
    {
        $this->simpleCol = $simpleCol;
    }

    /**
     * Returns the number of columns of simple widgets.
     *
     * @return int Number of columns.
     */
    public function getSimpleCol(): int
    {
        return $this->simpleCol;
    }

    /**
     * Backup the form settings to the stack.
     *
     * @internal Should only be used at the beginning of form_start. This allows
     *           a nested subform to change its settings without affecting its
     *           parent form.
     */
    public function backupFormSettings(): void
    {
        $settings = [
            'style'     => $this->style,
            'sizing'    => $this->sizing,
            'colSize'   => $this->colSize,
            'widgetCol' => $this->widgetCol,
            'labelCol'  => $this->labelCol,
            'simpleCol' => $this->simpleCol,
        ];

        array_push($this->settingsStack, $settings);
    }

    /**
     * Restore the form settings from the stack.
     *
     * @internal Should only be used at the end of form_end.
     * @see      backupFormSettings
     */
    public function restoreFormSettings(): void
    {
        if (count($this->settingsStack) < 1) {
            return;
        }

        $settings = array_pop($this->settingsStack);

        $this->style = $settings['style'];
        $this->sizing = $settings['sizing'];
        $this->colSize = $settings['colSize'];
        $this->widgetCol = $settings['widgetCol'];
        $this->labelCol = $settings['labelCol'];
        $this->simpleCol = $settings['simpleCol'];
    }

    /**
     * @param string $label
     * @param string $value
     *
     * @return string
     */
    public function formControlStaticFunction(string $label, string $value): string
    {
        return sprintf(
            '<div class="form-group from-group-%s">' .
            '<label class="col-sm-%s control-label">%s</label>' .
            '<div class="col-sm-%s">' .
            '<p class="form-control-static">%s</p>' .
            '</div>' .
            '</div>',
            $this->getSizing(),
            $this->getLabelCol(),
            $label,
            $this->getWidgetCol(),
            $value
        );
    }

    /**
     * Parses the given string and replaces all occurrences of .icon-[name] with the corresponding icon.
     *
     * @param string $text The text to parse
     *
     * @return string The HTML code with the icons
     */
    public function parseIconsFilter(string $text): string
    {
        $that = $this;

        return preg_replace_callback(
            '/\.([a-z]+)-([a-z0-9+-]+)/',
            function ($matches) use ($that) {
                return $that->iconFunction($matches[2], $matches[1]);
            },
            $text
        );
    }

    /**
     * Returns the HTML code for the given icon.
     *
     * @param string $icon    The name of the icon
     * @param string $iconSet The icon-set name
     *
     * @return string The HTML code for the icon
     */
    public function iconFunction(string $icon, string $iconSet = 'icon'): string
    {
        if ($iconSet == 'icon') {
            $iconSet = $this->iconPrefix;
        }

        $icon = str_replace('+', ' ' . $iconSet . '-', $icon);

        return sprintf('<%1$s class="%2$s %2$s-%3$s"></%1$s>', $this->iconTag, $iconSet, $icon);
    }
}
