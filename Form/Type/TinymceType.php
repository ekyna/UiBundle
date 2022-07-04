<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_key_exists;
use function is_string;
use function preg_replace;

/**
 * Class TinymceType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TinymceType extends AbstractType
{
    public function __construct(private readonly array $themes)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($html) { // transform
                return $html;
            },
            function ($html) { // reverse transform
                if (empty($html)) {
                    return null;
                }

                $html = preg_replace('~<p[^>]*>[&nbsp;|\s]*</p>~', '', $html);
                $html = preg_replace('~[\r\n]+~', '', $html);

                if (empty($html)) {
                    return null;
                }

                return $html;
            }
        ));
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $attrNormalizer = function (Options $options, $value) {
            $theme = isset($options['theme'])
            && is_string($options['theme'])
            && in_array($options['theme'], $this->themes)
                ? $options['theme'] : 'simple';

            if (array_key_exists('class', $value) && !empty($value['class'])) {
                $value['class'] .= ' tinymce';
            } else {
                $value['class'] = 'tinymce';
            }

            $value['data-theme'] = $theme;

            return $value;
        };

        $resolver
            ->setDefaults([
                'theme' => 'simple',
            ])
            ->setAllowedTypes('theme', 'string')
            ->setNormalizer('attr', $attrNormalizer);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return TextareaType::class;
    }
}
