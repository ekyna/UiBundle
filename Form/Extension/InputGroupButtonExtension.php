<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class InputGroupButtonExtension
 * @package Ekyna\Bundle\UiBundle\Form\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class InputGroupButtonExtension extends AbstractTypeExtension
{
    protected array $buttons = [];

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {

        if (!isset($this->buttons[$form->getName()])) {
            return;
        }

        $storedButtons = $this->buttons[$form->getName()];

        if (isset($storedButtons['prepend']) && $storedButtons['prepend'] !== null) {
            $view->vars['input_group_button_prepend'] = $storedButtons['prepend']->getForm()->createView();
        }

        if (isset($storedButtons['append']) && $storedButtons['append'] !== null) {
            $view->vars['input_group_button_append'] = $storedButtons['append']->getForm()->createView();
        }
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!isset($options['attr']) || !isset($options['attr']['input_group'])) {
            return;
        }

        if (isset($options['attr']['input_group']['button_prepend'])) {
            $this->storeButton(
                $this->addButton(
                    $builder,
                    $options['attr']['input_group']['button_prepend']
                ),
                $builder,
                'prepend'
            );
        }

        if (isset($options['attr']['input_group']['button_append'])) {
            $this->storeButton(
                $this->addButton(
                    $builder,
                    $options['attr']['input_group']['button_append']
                ),
                $builder,
                'append'
            );
        }
    }

    /**
     * Adds the button.
     *
     * @param FormBuilderInterface $builder
     * @param array                $config
     *
     * @return FormBuilderInterface
     */
    protected function addButton(FormBuilderInterface $builder, array $config): FormBuilderInterface
    {

        $options = (isset($config['options'])) ? $config['options'] : [];

        return $builder->create($config['name'], $config['type'], $options);
    }

    /**
     * Stores the button for later rendering.
     *
     * @param FormBuilderInterface $button
     * @param FormBuilderInterface $form
     * @param string               $position
     */
    protected function storeButton(FormBuilderInterface $button, FormBuilderInterface $form, string $position): void
    {

        if (!isset($this->buttons[$form->getName()])) {
            $this->buttons[$form->getName()] = [];
        }

        $this->buttons[$form->getName()][$position] = $button;
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [TextType::class];
    }
}
