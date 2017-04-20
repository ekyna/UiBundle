<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType as SymfonyCollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatableInterface;

use function array_replace;
use function Symfony\Component\Translation\t;

/**
 * Class CollectionType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CollectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $allowNormalizer = function (Options $options, $value) {
            if ($options['disabled']) {
                return false;
            }

            return $value;
        };

        $optionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };

        $resolver
            ->setDefaults([
                'error_bubbling'            => false,
                'by_reference'              => false,
                'allow_add'                 => true,
                'allow_delete'              => true,
                'allow_sort'                => false,
                'prototype'                 => true,
                'prototype_name'            => '__name__',
                'add_button_text'           => t('button.add', [], 'EkynaUi'),
                'add_button_class'          => 'btn btn-primary btn-sm',
                'delete_button_text'        => t('button.remove', [], 'EkynaUi'),
                'delete_button_class'       => 'btn btn-danger btn-sm',
                'delete_button_confirm'     => t('message.remove_confirm', [], 'EkynaUi'),
                'sub_widget_col'            => 11,
                'button_col'                => 1,
                'options'                   => [],
                'entry_type'                => TextType::class,
            ])
            ->setAllowedTypes('allow_sort', 'bool')
            ->setAllowedTypes('add_button_text', ['null', 'string', TranslatableInterface::class])
            ->setAllowedTypes('delete_button_text', ['null', 'string', TranslatableInterface::class])
            ->setAllowedTypes('delete_button_confirm', ['null', 'string', TranslatableInterface::class])
            ->setNormalizer('allow_add', $allowNormalizer)
            ->setNormalizer('allow_delete', $allowNormalizer)
            ->setNormalizer('allow_sort', $allowNormalizer)
            ->setNormalizer('options', $optionsNormalizer);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars = array_replace($view->vars, [
            'allow_add'                 => $options['allow_add'],
            'allow_delete'              => $options['allow_delete'],
            'allow_sort'                => $options['allow_sort'],
            'add_button_text'           => $options['add_button_text'],
            'add_button_class'          => $options['add_button_class'],
            'delete_button_text'        => $options['delete_button_text'],
            'delete_button_class'       => $options['delete_button_class'],
            'delete_button_confirm'     => $options['delete_button_confirm'],
            'sub_widget_col'            => $options['sub_widget_col'],
            'button_col'                => $options['button_col'],
            'prototype_name'            => $options['prototype_name'],
        ]);

        if (false === $view->vars['allow_delete']) {
            $view->vars['sub_widget_col'] += $view->vars['button_col'];
        }

        if ($form->getConfig()->hasAttribute('prototype')) {
            $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
        }

        if (false === $view->vars['allow_delete'] && false === $view->vars['allow_sort']) {
            $view->vars['sub_widget_col'] += $view->vars['button_col'];
            $view->vars['button_col'] = 0;
            if ($view->vars['sub_widget_col'] > 12) {
                $view->vars['sub_widget_col'] = 12;
            }
        } else {
            $view->vars['sub_widget_col'] = $options['sub_widget_col'];
            $view->vars['button_col'] = $options['button_col'];
        }
    }

    public function getParent(): ?string
    {
        return SymfonyCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'ekyna_collection';
    }
}
