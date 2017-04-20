<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Class CollectionPositionType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CollectionPositionType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$view->parent->parent) {
            return;
        }

        $collectionId = $view->parent->parent->vars['id'];

        $view->vars['attr'] = array_replace($view->vars['attr'], [
            'data-collection-role' => 'position',
            'data-collection'      => $collectionId,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return HiddenType::class;
    }
}
