<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_map;

/**
 * Class FormActionsType
 * @package Ekyna\Bundle\AdminBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FormActionsType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['buttons'] as $name => $config) {
            $this->addButton($builder, $name, $config);
        }
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (0 === $form->count()) {
            return;
        }

        array_map([$this, 'validateButton'], $form->all());
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'buttons' => [],
            'options' => [],
            'mapped'  => false,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix(): string
    {
        return 'form_actions';
    }

    /**
     * Adds a button
     *
     * @param FormBuilderInterface $builder
     * @param string               $name
     * @param array                $config
     */
    protected function addButton(FormBuilderInterface $builder, string $name, array $config): void
    {
        $builder->add($name, $config['type'], $config['options'] ?: []);
    }

    /**
     * Validates if child is a Button
     *
     * @param FormInterface $field
     *
     * @throws InvalidArgumentException
     */
    protected function validateButton(FormInterface $field): void
    {
        if (!$field instanceof Button) {
            throw new InvalidArgumentException('Children of FormActionsType must be instances of the Button class');
        }
    }
}
