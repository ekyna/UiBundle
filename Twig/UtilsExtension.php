<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Twig;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Ekyna\Bundle\UiBundle\Util\Truncator;
use RuntimeException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

use function json_encode;
use function json_last_error_msg;

use const JSON_FORCE_OBJECT;
use const JSON_HEX_APOS;
use const JSON_NUMERIC_CHECK;
use const JSON_PRESERVE_ZERO_FRACTION;

/**
 * UtilsExtension
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UtilsExtension extends AbstractExtension
{
    private ?Inflector $inflector;

    public function getFilters(): array
    {
        return [
            new TwigFilter('json_encode_data', [$this, 'jsonEncodeDataAttribute']),
            new TwigFilter('paragraph_to_break', [$this, 'paragraphToBreak'], ['is_safe' => ['html']]),
            new TwigFilter('truncate_html', [$this, 'truncateHtml'], ['is_safe' => ['html']]),
            new TwigFilter('pluralize', [$this, 'pluralize']),
            new TwigFilter('base64_inline_file', [$this, 'base64InlineFile']),
            new TwigFilter('base64_inline_data', [$this, 'base64InlineData']),
            new TwigFilter('unset', [$this, 'unset']),
            new TwigFilter('float', fn($v) => (float)$v),
            new TwigFilter('string', fn($v) => (string)$v),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('unset', [$this, 'unset']),
        ];
    }

    /**
     * Json encode the given data for html data-* attribute.
     *
     * @param mixed $data
     *
     * @return string
     */
    public function jsonEncodeDataAttribute($data): string
    {
        $opts = JSON_HEX_APOS | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION;

        if (empty($data)) {
            $opts = $opts | JSON_FORCE_OBJECT;
        }

        if (false === $json = json_encode($data, $opts)) {
            throw new RuntimeException(json_last_error_msg());
        }

        return $json;
    }

    /**
     *  Replaces paragraphs by line breaks.
     */
    public function paragraphToBreak(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        return strip_tags(strtr($html, ['</p>' => '<br><br>']), '<br><a><span><em><strong>');
    }

    /**
     * Returns a truncated html string.
     */
    public function truncateHtml(?string $html, int $limit = 128, string $endChar = '&hellip;'): string
    {
        if (empty($html)) {
            return '';
        }

        return (new Truncator($html))->truncate($limit, $endChar);
    }

    /**
     * Pluralize the given string.
     */
    public function pluralize(?string $string): string
    {
        if (empty($string)) {
            return '';
        }

        return $this->getInflector()->pluralize($string);
    }

    /**
     * Encodes and inlines the given file path.
     */
    public function base64InlineFile(string $path, string $mimeType, array $parameters = []): ?string
    {
        if (file_exists($path)) {
            return $this->base64InlineData(file_get_contents($path), $mimeType, $parameters);
        }

        return null;
    }

    /**
     * Encodes and inlines the given binary data.
     *
     * @param string|resource $data
     */
    public function base64InlineData($data, string $mimeType, array $parameters = []): string
    {
        $output = 'data:' . $mimeType;
        foreach ($parameters as $name => $value) {
            $output .= ';' . $name . '=' . $value;
        }

        if (is_resource($data)) {
            $data = stream_get_contents($data);
        }

        return $output . ';base64,' . base64_encode($data);
    }

    /**
     * Unsets the given array's key.
     */
    public function unset(array $array, string $key): void
    {
        unset($array[$key]); // TODO Without reference, nor returning something ? oO
    }

    /**
     * Returns the doctrine inflector.
     */
    private function getInflector(): Inflector
    {
        if ($this->inflector) {
            return $this->inflector;
        }

        return $this->inflector = InflectorFactory::create()->build();
    }
}
