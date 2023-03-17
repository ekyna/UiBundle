<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service;

use Locale;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Currencies;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_pop;
use function strtolower;

/**
 * Class UiHelper
 * @package Ekyna\Bundle\UiBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class IntlHelper
{
    private RequestStack        $requestStack;
    private TranslatorInterface $translator;
    private string              $defaultLocale;

    private ?string $currentLocale = null;
    private array   $localeStack   = [];


    /**
     * Constructor.
     *
     * @param RequestStack        $requestStack
     * @param TranslatorInterface $translator
     * @param string              $defaultLocale
     */
    public function __construct(RequestStack $requestStack, TranslatorInterface $translator, string $defaultLocale)
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Returns the language for the given locale.
     *
     * @param string      $locale
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getLanguage(string $locale, string $displayLocale = null): string
    {
        return Locale::getDisplayLanguage($locale, $displayLocale ?? $this->getCurrentLocale());
    }

    /**
     * Returns the current locale.
     *
     * @return string
     */
    private function getCurrentLocale(): string
    {
        if ($this->currentLocale) {
            return $this->currentLocale;
        }

        if ($request = $this->requestStack->getMainRequest()) {
            return $this->currentLocale = $request->getLocale();
        }

        return $this->currentLocale = $this->defaultLocale;
    }

    /**
     * Returns the country name for the given code.
     *
     * @param string      $code
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getCountry(string $code, string $displayLocale = null): string
    {
        return Countries::getName($code, $displayLocale ?? $this->getCurrentLocale());
    }

    /**
     * Returns the currency name for the given code.
     *
     * @param string      $code
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getCurrencyName(string $code, string $displayLocale = null): string
    {
        return Currencies::getName($code, $displayLocale ?? $this->getCurrentLocale());
    }

    /**
     * Returns the currency symbol for the given code.
     *
     * @param string      $code
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getCurrencySymbol(string $code, string $displayLocale = null): string
    {
        return Currencies::getSymbol($code, $displayLocale ?? $this->getCurrentLocale());
    }

    /**
     * Sets the translator locator.
     *
     * @param string $locale
     */
    public function setTranslatorLocale(string $locale): void
    {
        $this->localeStack[] = $this->translator->getLocale();

        $this->translator->setLocale(strtolower($locale));
    }

    /**
     * Reverts the translator locale.
     */
    public function revertTranslatorLocale(): void
    {
        if (null === $locale = array_pop($this->localeStack)) {
            return;
        }

        $this->translator->setLocale($locale);
    }
}
