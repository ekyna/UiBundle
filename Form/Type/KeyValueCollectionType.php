<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Ekyna\Bundle\UiBundle\Form\DataTransformer\HashToKeyValueArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class KeyValueCollectionType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Bart van den Burg <bart@burgov.nl>
 * @see     https://github.com/Burgov/KeyValueFormBundle/blob/master/Form/Type/KeyValueType.php
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class KeyValueCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new HashToKeyValueArrayTransformer($options['use_container_object']));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $e) {
            $input = $e->getData();

            if (null === $input) {
                return;
            }

            $output = [];

            foreach ($input as $key => $value) {
                $output[] = [
                    'key'   => $key,
                    'value' => $value,
                ];
            }

            $e->setData($output);
        }, 1);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'entry_type'           => KeyValueType::class,
                'entry_options'        => function (Options $options) {
                    return [
                        'key_type'      => $options['key_type'],
                        'key_options'   => $options['key_options'],
                        'value_type'    => $options['value_type'],
                        'value_options' => $options['value_options'],
                        'allowed_keys'  => $options['allowed_keys'],
                    ];
                },
                'key_type'             => TextType::class,
                'key_options'          => [],
                'value_type'           => TextType::class,
                'value_options'        => [],
                'allowed_keys'         => null,
                'use_container_object' => false,
            ])
            ->setRequired(['value_type'])
            ->setAllowedTypes('allowed_keys', ['null', 'array']);
    }

    public function getParent(): ?string
    {
        return CollectionType::class;
    }
}
