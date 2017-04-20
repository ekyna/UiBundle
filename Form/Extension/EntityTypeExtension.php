<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Extension;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityTypeExtension
 * @package Ekyna\Bundle\UiBundle\Form\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EntityTypeExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'add_route'        => false,
            'add_route_params' => [],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!empty($options['add_route'])) {
            $view->vars['add_route'] = $options['add_route'];
            $view->vars['add_route_params'] = $options['add_route_params'];
        }
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [EntityType::class];
    }
}
