<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ButtonTypeExtension
 * @package Ekyna\Bundle\UiBundle\Form\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ButtonTypeExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['button_class'] = $form->getConfig()->getOption('button_class');
        $view->vars['as_link'] = $form->getConfig()->getOption('as_link');
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['button_class', 'as_link']);
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [ButtonType::class];
    }
}
