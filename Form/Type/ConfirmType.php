<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Contracts\Translation\TranslatableInterface;

use function Symfony\Component\Translation\t;

/**
 * Class ConfirmType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('confirm', Type\CheckboxType::class, [
            'label'       => $options['message'],
            'attr'        => ['align_with_widget' => true],
            'required'    => true,
            'constraints' => [
                new Constraints\IsTrue(),
            ],
        ]);

        if (!$options['buttons']) {
            return;
        }

        $buttons = [
            'submit' => [
                'type'    => Type\SubmitType::class,
                'options' => [
                    'button_class' => $options['submit_class'],
                    'label'        => $options['submit_label'],
                    'attr'         => $options['submit_icon'] ? ['icon' => $options['submit_icon']] : [],
                ],
            ],
        ];

        if ($options['cancel_path']) {
            $buttons['cancel'] = [
                'type'    => Type\ButtonType::class,
                'options' => [
                    'label'        => t('button.cancel', [], 'EkynaUi'),
                    'button_class' => 'default',
                    'as_link'      => true,
                    'attr'         => [
                        'class' => 'form-cancel-btn',
                        'icon'  => 'remove',
                        'href'  => $options['cancel_path'],
                    ],
                ],
            ];
        }

        $builder->add('actions', FormActionsType::class, [
            'buttons' => $buttons,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'message'      => t('message.remove_confirm', [], 'EkynaUi'),
                'submit_label' => t('button.confirm', [], 'EkynaUi'),
                'submit_class' => 'danger',
                'submit_icon'  => 'remove',
                'cancel_path'  => null,
                'buttons'      => true,
            ])
            ->setAllowedTypes('message', TranslatableInterface::class)
            ->setAllowedTypes('buttons', 'bool')
            ->setAllowedTypes('submit_label', TranslatableInterface::class)
            ->setAllowedTypes('submit_class', 'string')
            ->setAllowedTypes('submit_icon', ['string', 'null'])
            ->setAllowedTypes('cancel_path', ['string', 'null']);
    }
}
