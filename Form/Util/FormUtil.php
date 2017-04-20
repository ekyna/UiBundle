<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Util;

use Ekyna\Bundle\UiBundle\Form\Type\FormActionsType;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormConfigBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

use function Symfony\Component\Translation\t;

/**
 * Class FormAttributes
 * @package Ekyna\Bundle\UiBundle\Form\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FormUtil
{
    public static function addErrorsFromResourceEvent(FormInterface $form, ResourceEventInterface $event): void
    {
        foreach ($event->getErrors() as $error) {
            $form->addError(new FormError($error->getMessage()));
        }
    }

    public static function addFooter(FormInterface $form, array $options = []): void
    {
        $submitLabel = $options['submit_label'] ?? t('button.save', [], 'EkynaUi');
        $submitClass = $options['submit_class'] ?? 'primary';
        $submitIcon = $options['submit_icon'] ?? 'ok';

        $buttons = [
            'submit' => [
                'type'    => Type\SubmitType::class,
                'options' => [
                    'button_class' => $submitClass,
                    'label'        => $submitLabel,
                    'attr'         => ['icon' => $submitIcon],
                ],
            ],
        ];

        if (isset($options['cancel_path'])) {
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

        $form->add('actions', FormActionsType::class, [
            'buttons' => $buttons,
        ]);
    }

    /**
     * Adds the class the form view's attributes.
     *
     * @param FormView $view
     * @param string   $class
     */
    public static function addClass(FormView $view, string $class): void
    {
        $attributes = $view->vars['attr'];

        $classes = isset($attributes['class']) ? explode(' ', $attributes['class']) : [];
        if (!in_array($class, $classes, true)) {
            $classes[] = $class;
        }
        $attributes['class'] = implode(' ', $classes);

        $view->vars['attr'] = $attributes;
    }

    /**
     * Binds the form events to children form fields.
     *
     * Children form with 'inherit_data' = true do not receive form events.
     * @see https://github.com/symfony/symfony/issues/8834#issuecomment-55785696
     *
     * @param FormConfigBuilderInterface $builder  The parent form builder.
     * @param array                      $events   The names of the events to bind ([$name => $priority]).
     * @param array                      $children The names of the children fields.
     */
    public static function bindFormEventsToChildren(
        FormConfigBuilderInterface $builder,
        array $events,
        array $children
    ): void {
        if (empty($events) || empty($children)) {
            return;
        }

        foreach ($events as $name => $priority) {
            if (is_int($name) && is_scalar($priority)) {
                $name = $priority;
                $priority = 0;
            }

            $builder->addEventListener($name, function (FormEvent $event) use ($name, $children) {
                $form = $event->getForm();
                foreach ($children as $field) {
                    $child = $form->get($field);
                    $child->getConfig()->getEventDispatcher()->dispatch(
                        new FormEvent($child, $event->getData()),
                        $name
                    );
                }
            }, $priority);
        }
    }
}
