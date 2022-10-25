<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Ekyna\Component\Resource\Model\DateRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Symfony\Component\Translation\t;

/**
 * Class DateRangeType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $type = $options['time'] ? DateTimeType::class : DateType::class;

        $builder
            ->add('start', $type, [
                'label' => t('field.start_date', [], 'EkynaUi'),
            ])
            ->add('end', $type, [
                'label' => t('field.end_date', [], 'EkynaUi'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DateRange::class,
            'time'       => false,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return 'ekyna_ui_date_range';
    }
}
