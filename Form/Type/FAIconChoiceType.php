<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Ekyna\Bundle\ResourceBundle\Form\Type\ConstantChoiceType;
use Ekyna\Bundle\UiBundle\Model\FAIcons;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

/**
 * Class FAIconChoiceType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FAIconChoiceType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label'        => t('field.icon', [], 'EkynaUi'),
                'class'        => FAIcons::class,
                'admin_helper' => 'CMS_TAG_ICON',
                'placeholder'  => t('value.choose', [], 'EkynaUi'),
                'select2'      => false,
                'attr'         => [
                    'class'     => 'fa-icon-choice',
                    'help_text' => t('message.fa_icon_cheatsheet', [], 'EkynaUi'),
                ],
            ]);
    }

    public function getParent(): ?string
    {
        return ConstantChoiceType::class;
    }
}
