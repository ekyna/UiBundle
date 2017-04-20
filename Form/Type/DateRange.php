<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

/**
 * Class DateRange
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DateRange extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $type = $options['time'] ? DateTimeType::class : DateType::class;

        $builder
            ->add('startDate', $type, [
                'label' => t('field.start_date', [], 'EkynaUi'),
            ])
            ->add('endDate', $type, [
                'label' => t('field.end_date', [], 'EkynaUi'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'time'         => true,
                'inherit_data' => true,
            ]);
    }
}
