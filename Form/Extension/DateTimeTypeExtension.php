<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateTimeTypeExtension
 * @package Ekyna\Bundle\UiBundle\Form\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DateTimeTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            // TODO Localized format ? 'format' => 'dd/MM/yyyy',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [DateTimeType::class, DateType::class];
    }
}
