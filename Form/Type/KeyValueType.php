<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_combine;
use function array_replace;
use function Symfony\Component\Translation\t;

/**
 * Class KeyValueType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Bart van den Burg <bart@burgov.nl>
 * @see     https://github.com/Burgov/KeyValueFormBundle/blob/master/Form/Type/KeyValueRowType.php
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class KeyValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (null === $options['allowed_keys']) {
            $builder->add('key', TextType::class, array_replace([
                'label' => t('field.key', [], 'EkynaUi'),
            ], $options['key_options']));
        } else {
            $builder->add('key', ChoiceType::class, array_replace([
                'label'   => t('field.key', [], 'EkynaUi'),
                'choices' => array_combine($options['allowed_keys'], $options['allowed_keys']),
            ], $options['key_options']));
        }

        $builder->add('value', $options['value_type'], $options['value_options']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $valueOptionsDefaults = [
            'label' => t('field.value', [], 'EkynaUi'),
        ];

        $resolver
            ->setDefaults([
                'key_type'      => TextType::class,
                'key_options'   => [],
                'value_type'    => TextType::class,
                'value_options' => $valueOptionsDefaults,
                'allowed_keys'  => null,
            ])
            ->setAllowedTypes('key_type', 'string')
            ->setAllowedTypes('key_options', 'array')
            ->setAllowedTypes('value_type', 'string')
            ->setAllowedTypes('value_options', 'array')
            ->setAllowedTypes('allowed_keys', ['null', 'array'])
            ->setNormalizer(
                'value_options',
                function (Options $options, $value) use ($valueOptionsDefaults) {
                    return array_replace($valueOptionsDefaults, $value);
                }
            );
    }

    public function getBlockPrefix(): string
    {
        return 'ekyna_key_value';
    }
}
