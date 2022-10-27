<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Ekyna\Bundle\UiBundle\Form\DataTransformer\AnniversaryToArrayModelTransformer;
use Ekyna\Component\Commerce\Common\Util\DateUtil;
use Ekyna\Component\Resource\Bridge\Symfony\Validator\Anniversary;
use Ekyna\Component\Resource\Locale\LocaleProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_flip;
use function Symfony\Component\Translation\t;

/**
 * Class AnniversaryType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AnniversaryType extends AbstractType
{
    public function __construct(
        private readonly LocaleProviderInterface $localeProvider
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $monthOptions = [
            'label'    => t('field.month', [], 'EkynaUi'),
            'choices'  => $this->getMonthChoices(),
            'required' => $options['required'],
        ];

        $dayOptions = [
            'label'    => t('field.day', [], 'EkynaUi'),
            'choices'  => $this->getDayChoices(),
            'required' => $options['required'],
        ];

        if (!$options['required']) {
            $monthOptions['placeholder'] = t('field.month', [], 'EkynaUi');
            $dayOptions['placeholder'] = t('field.day', [], 'EkynaUi');
        }

        $builder
            ->addModelTransformer(new AnniversaryToArrayModelTransformer())
            ->add('month', ChoiceType::class, $monthOptions)
            ->add('day', ChoiceType::class, $dayOptions);
    }

    private function getMonthChoices(): array
    {
        $choices = [];

        $locale = $this->localeProvider->getCurrentLocale();

        $choices += array_flip(DateUtil::getMonths($locale));

        return $choices;
    }

    private function getDayChoices(): array
    {
        $choices = [];

        for ($i = 1; $i <= 31; $i++) {
            $choices[$i] = $i;
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('constraints', [
            new Anniversary(),
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return 'ekyna_ui_anniversary';
    }
}
