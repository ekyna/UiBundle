<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Twig;

use Ekyna\Bundle\UiBundle\Service\IntlHelper;
use Ekyna\Bundle\UiBundle\Service\UiRenderer;
use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class UiExtension
 * @package Ekyna\Bundle\UiBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UiExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ui_assets_base_url',
                [UiRenderer::class, 'renderAssetsBaseUrl'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_content_stylesheets',
                [UiRenderer::class, 'renderContentStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_forms_stylesheets',
                [UiRenderer::class, 'renderFormsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_fonts_stylesheets',
                [UiRenderer::class, 'renderFontsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_no_image',
                [UiRenderer::class, 'renderNoImage'],
                ['is_safe' => ['html'],]
            ),
            new TwigFunction(
                'ui_link',
                [UiRenderer::class, 'renderLink'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_button',
                [UiRenderer::class, 'renderButton'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_dropdown',
                [UiRenderer::class, 'renderDropdown'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'trans_set_locale',
                [IntlHelper::class, 'setTranslatorLocale']
            ),
            new TwigFunction(
                'trans_revert_locale',
                [IntlHelper::class, 'revertTranslatorLocale']
            ),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'language',
                [IntlHelper::class, 'getLanguage']
            ),
            new TwigFilter(
                'country',
                [IntlHelper::class, 'getCountry']
            ),
            new TwigFilter(
                'currency_name',
                [IntlHelper::class, 'getCurrencyName']
            ),
            new TwigFilter(
                'currency_symbol',
                [IntlHelper::class, 'getCurrencySymbol']
            ),
            new TwigFilter(
                'ui_clipboard_copy',
                [UiRenderer::class, 'renderClipboardCopy'],
                ['is_safe' => ['html']]
            ),
            new TwigFilter(
                'ui_fa_icon',
                [UiRenderer::class, 'renderFaIcon'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function getTests(): array
    {
        return [
            new TwigTest(
                'form',
                function ($var): bool {
                    return $var instanceof FormView;
                }
            ),
        ];
    }
}
