<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormStaticControlType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FormStaticControlType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'disabled' => true,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix(): ?string
    {
        return 'ekyna_static';
    }
}
