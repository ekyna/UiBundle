<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Service;

use Ekyna\Bundle\UiBundle\Model\FAIcons;
use Ekyna\Bundle\UiBundle\Model\UiButton;
use Ekyna\Component\Resource\Exception\UnexpectedTypeException;
use InvalidArgumentException;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatableInterface;
use Twig\Environment;
use Twig\TemplateWrapper;

/**
 * Class UiRenderer
 * @package Ekyna\Bundle\UiBundle\Service\Ui
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UiRenderer
{
    private Environment $twig;
    private array       $config;

    private ?AssetExtension          $assetExtension          = null;
    private ?HttpFoundationExtension $httpExtension           = null;
    private ?TemplateWrapper         $template                = null;
    private ?OptionsResolver         $buttonOptionsResolver   = null;
    private ?OptionsResolver         $dropdownOptionsResolver = null;


    public function __construct(Environment $twig, array $config)
    {
        $this->twig = $twig;
        $this->config = array_replace([
            'stylesheets'       => [
                'contents' => [],
                'forms'    => [],
                'fonts'    => [],
            ],
            'no_image_path'     => null,
            'controls_template' => '@EkynaUi/controls.html.twig',
        ], $config);
    }

    /**
     * Renders the content stylesheet link.
     */
    public function renderContentStylesheets(): string
    {
        $output = '';

        foreach ($this->config['stylesheets']['contents'] as $path) {
            $output .= $this->buildStylesheetTag($path);
        }

        return $output;
    }

    /**
     * Builds a stylesheet tag.
     */
    public function buildStylesheetTag(string $path): string
    {
        return '<link href="' . $this->getAssetUrl($path) . '" rel="stylesheet" type="text/css">' . "\n";
    }

    /**
     * Returns the asset url.
     */
    private function getAssetUrl(string $path): string
    {
        return $this->getHttpExtension()->generateAbsoluteUrl(
            $this->getAssetExtension()->getAssetUrl($path)
        );
    }

    /**
     * Renders the assets base url attribute.
     *
     * @noinspection PhpUnused
     */
    public function renderAssetsBaseUrl(): string
    {
        $url = substr($this->getAssetUrl('fake.css'), 0, -9);

        return ' data-asset-base-url="' . $url . '"';
    }

    /**
     * Renders the forms stylesheets links.
     *
     * @noinspection PhpUnused
     */
    public function renderFormsStylesheets(): string
    {
        $output = '';

        foreach ($this->config['stylesheets']['forms'] as $path) {
            $output .= $this->buildStylesheetTag($path);
        }

        return $output;
    }

    /**
     * Renders the fonts stylesheets links.
     *
     * @noinspection PhpUnused
     */
    public function renderFontsStylesheets(): string
    {
        $output = '';

        foreach ($this->config['stylesheets']['fonts'] as $path) {
            $output .= $this->buildStylesheetTag($path);
        }

        return $output;
    }

    /**
     * Renders the "no image" img.
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function renderNoImage(array $attributes = []): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getTemplate()->renderBlock(
            'no_image',
            [
                'no_image_path' => $this->getAssetUrl($this->config['no_image_path']),
                'attr'          => $attributes,
            ]
        );
    }

    /**
     * Returns the control's template.
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    private function getTemplate(): TemplateWrapper
    {
        if ($this->template) {
            return $this->template;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->template = $this->twig->load($this->config['controls_template']);
    }

    /**
     * Renders the link.
     */
    public function renderLink(string $href, string $label = '', array $options = [], array $attributes = []): string
    {
        $options['type'] = 'link';
        $options['path'] = $href;

        return $this->renderButton($label, $options, $attributes);
    }

    /**
     * Renders the button.
     *
     * @param TranslatableInterface|UiButton|string $label
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function renderButton($label = '', array $options = [], array $attributes = []): string
    {
        if ($label instanceof UiButton) {
            $options = $label->getOptions();
            $attributes = $label->getAttributes();
            $label = $label->getLabel();
        } elseif (!$label instanceof TranslatableInterface && !is_string($label)) {
            throw new UnexpectedTypeException($label, [TranslatableInterface::class, UiButton::class, 'string']);
        }

        $options = $this->getButtonOptions()->resolve($options);

        $tag = 'button';
        $classes = ['btn', 'btn-' . $options['theme'], 'btn-' . $options['size']];
        $defaultAttr = [
            'class' => sprintf('btn btn-%s btn-%s', $options['theme'], $options['size']),
        ];
        if ($options['type'] == 'link') {
            if (empty($options['path'])) {
                throw new InvalidArgumentException('"path" option must be defined for "link" button type.');
            }
            $tag = 'a';
            $defaultAttr['href'] = $options['path'];
        } else {
            $defaultAttr['type'] = $options['type'];
        }

        if (array_key_exists('class', $attributes)) {
            $classes = array_merge($classes, explode(' ', $attributes['class']));
            unset($attributes['class']);
        }
        $defaultAttr['class'] = implode(' ', $classes);
        $attributes = array_merge($defaultAttr, $attributes);

        $icon = '';
        if (!empty($options['icon'])) {
            $icon = ($options['fa_icon'] ? 'fa fa-' : 'glyphicon glyphicon-') . $options['icon'];
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getTemplate()->renderBlock(
            'button',
            [
                'tag'          => $tag,
                'attr'         => $attributes,
                'label'        => $label,
                'icon'         => $icon,
                'trans_domain' => $options['trans_domain'],
            ]
        );
    }

    /**
     * Returns the button options resolver.
     */
    private function getButtonOptions(): OptionsResolver
    {
        if (null === $this->buttonOptionsResolver) {
            $this->buttonOptionsResolver = new OptionsResolver();
            $this->buttonOptionsResolver
                ->setDefaults(
                    [
                        'type'         => 'button',
                        'theme'        => 'default',
                        'size'         => 'sm',
                        'icon'         => null,  // https://www.glyphicons.com/sets/basic/
                        'fa_icon'      => false, // https://fontawesome.com/v4.7/icons/
                        'path'         => null,
                        'trans_domain' => null,
                    ]
                )
                ->setRequired(['type', 'theme', 'size'])
                ->setAllowedValues('type', ['link', 'button', 'submit', 'reset'])
                ->setAllowedTypes('theme', 'string')
                ->setAllowedValues('size', ['xs', 'sm', 'md', 'lg'])
                ->setAllowedTypes('icon', ['string', 'null'])
                ->setAllowedTypes('fa_icon', 'bool')
                ->setAllowedTypes('path', ['string', 'null'])
                ->setAllowedTypes('trans_domain', ['string', 'null']);
        }

        return $this->buttonOptionsResolver;
    }

    /**
     * Renders the dropdown.
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function renderDropdown(
        array $actions,
        array $options = [],
        array $attributes = []
    ): string {
        $options = $this->getDropdownOptions()->resolve($options);

        $classes = ['btn', 'btn-' . $options['theme'], 'btn-' . $options['size'], 'dropdown-toggle'];
        unset($options['theme'], $options['size']);

        if (array_key_exists('class', $attributes)) {
            array_push($classes, ...explode(' ', $attributes['class']));
            unset($attributes['class']);
        }
        $attributes = array_replace(
            $attributes,
            [
                'aria-expanded' => 'false',
                'aria-haspopup' => 'true',
                'class'         => implode(' ', $classes),
                'data-toggle'   => 'dropdown',
                'type'          => 'button',
            ]
        );

        if (!empty($options['icon'])) {
            $options['icon'] = ($options['fa_icon'] ? 'fa fa-' : 'glyphicon glyphicon-') . $options['icon'];
        }

        // TODO validate actions : label => path

        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getTemplate()->renderBlock(
            'dropdown',
            array_replace(
                $options,
                [
                    'attr'    => $attributes,
                    'actions' => $actions,
                ]
            )
        );
    }

    /**
     * Returns the dropdown options resolver.
     */
    private function getDropdownOptions(): OptionsResolver
    {
        if (null === $this->dropdownOptionsResolver) {
            $this->dropdownOptionsResolver = new OptionsResolver();
            $this->dropdownOptionsResolver
                ->setDefaults(
                    [
                        'label'        => null,
                        'trans_domain' => null,
                        'theme'        => 'default',
                        'size'         => 'sm',
                        'icon'         => null,
                        'fa_icon'      => false,
                        'right'        => false,
                    ]
                )
                ->setRequired(['theme', 'size'])
                ->setAllowedTypes('label', ['null', 'string'])
                ->setAllowedTypes('trans_domain', ['string', 'null'])
                ->setAllowedTypes('theme', 'string')
                ->setAllowedValues('size', ['xs', 'sm', 'md', 'lg'])
                ->setAllowedTypes('icon', ['string', 'null'])
                ->setAllowedTypes('fa_icon', 'bool')
                ->setAllowedTypes('right', 'bool');
        }

        return $this->dropdownOptionsResolver;
    }

    /**
     * Renders a font awesome icon.
     *
     * @noinspection PhpUnused
     */
    public function renderFaIcon(string $icon = null, string $classes = null): ?string
    {
        if (is_null($icon) || !FAIcons::isValid($icon, false)) {
            return null;
        }

        return sprintf('<i class="fa fa-%s %s"></i>', $icon, $classes);
    }

    /**
     * Returns the asset twig extension.
     */
    private function getAssetExtension(): AssetExtension
    {
        if ($this->assetExtension) {
            return $this->assetExtension;
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        return $this->assetExtension = $this->twig->getExtension(AssetExtension::class);
    }

    /**
     * Returns the http twig extension.
     */
    private function getHttpExtension(): HttpFoundationExtension
    {
        if ($this->httpExtension) {
            return $this->httpExtension;
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        return $this->httpExtension = $this->twig->getExtension(HttpFoundationExtension::class);
    }
}
