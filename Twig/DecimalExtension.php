<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Twig;

use Decimal\Decimal;
use Twig\Error\RuntimeError;
use Twig\Extension\AbstractExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\TwigFilter;

use function abs;
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

    /** @noinspection PhpMissingDocCommentInspection */
    public function abs($value)
    {
        if ($value instanceof Decimal) {
            return $value->abs();
        }

        return abs($value);
    }

    /** @noinspection PhpMissingDocCommentInspection */
    public function round($value, $precision = 0, $method = 'common')
    {
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

    /**
     * @param Decimal|string|float|int $amount
     */
    public function formatCurrency($amount, string $currency, array $attrs = [], string $locale = null): string
    {
        if ($amount instanceof Decimal) {
            $amount = $amount->toFloat();
        }

        return $this->intlExtension->formatCurrency($amount, $currency, $attrs, $locale);
    }

    /**
     * @param Decimal|string|float|int $number
     */
    public function formatNumber(
        $number,
        array $attrs = [],
        string $style = 'decimal',
        string $type = 'default',
        string $locale = null
    ): string {
        if ($number instanceof Decimal) {
            $number = $number->toFloat();
        }

        return $this->intlExtension->formatNumber($number, $attrs, $style, $type, $locale);
    }

    /**
     * @param Decimal|string|float|int $number
     */
    public function formatNumberStyle(
        string $style,
               $number,
        array  $attrs = [],
        string $type = 'default',
        string $locale = null
    ): string {
        return $this->formatNumber($number, $attrs, $style, $type, $locale);
    }
}
