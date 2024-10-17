<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Twig;

use Decimal\Decimal;
use Twig\Error\RuntimeError;
use Twig\Extension\AbstractExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\TwigFilter;

use function abs;
use function is_string;
use function twig_round;

/**
 * Class DecimalExtension
 * @package Ekyna\Bundle\ResourceBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DecimalExtension extends AbstractExtension
{
    private IntlExtension $intlExtension;

    public function __construct(IntlExtension $intlExtension)
    {
        $this->intlExtension = $intlExtension;
    }

    public function getFilters(): array
    {
        return [
            // Intl extension filters replacements
            new TwigFilter('format_currency', [$this, 'formatCurrency']),
            new TwigFilter('format_number', [$this, 'formatNumber']),
            new TwigFilter('format_*_number', [$this, 'formatNumberStyle']),

            // Core extension filters replacements
            new TwigFilter('abs', [$this, 'abs']),
            new TwigFilter('round', [$this, 'round']),
        ];
    }

    public function abs(Decimal|string|float|int|null $value): Decimal|float|int
    {
        if ($value instanceof Decimal) {
            return $value->abs();
        }

        return abs($value);
    }

    public function round(
        Decimal|string|float|int|null $value,
        int                           $precision = 0,
        string                        $method = 'common'
    ): Decimal|float|int {
        if (!$value instanceof Decimal) {
            return twig_round($value, $precision, $method);
        }

        if ('common' === $method) {
            return $value->round($precision);
        }

        if ('ceil' !== $method && 'floor' !== $method) {
            throw new RuntimeError('The round filter only supports the "common", "ceil", and "floor" methods.');
        }

        return $value->mul(10 ** $precision)->{$method}()->div(10 ** $precision);
    }

    public function formatCurrency(
        Decimal|string|float|int|null $amount,
        string                        $currency,
        array                         $attrs = [],
        string                        $locale = null
    ): string {
        return $this
            ->intlExtension
            ->formatCurrency($this->convert($amount), $currency, $attrs, $locale);
    }

    public function formatNumber(
        Decimal|string|float|int|null $number,
        array                         $attrs = [],
        string                        $style = 'decimal',
        string                        $type = 'default',
        string                        $locale = null
    ): string {
        return $this
            ->intlExtension
            ->formatNumber($this->convert($number), $attrs, $style, $type, $locale);
    }

    public function formatNumberStyle(
        string                        $style,
        Decimal|string|float|int|null $number,
        array                         $attrs = [],
        string                        $type = 'default',
        string                        $locale = null
    ): string {
        return $this->formatNumber($number, $attrs, $style, $type, $locale);
    }

    private function convert(Decimal|string|float|int|null $number): float|null
    {
        if ($number instanceof Decimal) {
            return $number->toFloat();
        }

        if (is_string($number)) {
            return (float)$number;
        }

        return $number;
    }
}
