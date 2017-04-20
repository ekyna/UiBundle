<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Form\Type;

use Ekyna\Bundle\UiBundle\Form\Util\FormUtil;
use Ekyna\Bundle\UiBundle\Service\Geo\UserCountryGuesser;
use libphonenumber\PhoneNumberFormat as Formats;
use libphonenumber\PhoneNumberType as Types;
use libphonenumber\PhoneNumberUtil;
use Misd\PhoneNumberBundle\Form\DataTransformer\PhoneNumberToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PhoneNumberType
 * @package Ekyna\Bundle\UiBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class PhoneNumberType extends AbstractType
{
    private static ?array $countryChoices = null;

    private UserCountryGuesser $userCountryGuesser;


    public function __construct(UserCountryGuesser $userCountryGuesser)
    {
        $this->userCountryGuesser = $userCountryGuesser;
    }

    private static function getCountryChoices(): array
    {
        if (self::$countryChoices) {
            return self::$countryChoices;
        }

        $util = PhoneNumberUtil::getInstance();

        $countries = [];
        if (empty($countries)) {
            foreach ($util->getSupportedRegions() as $country) {
                $countries[$country] = $util->getCountryCodeForRegion($country);
            }
        }

        $countryChoices = [];
        foreach (Countries::getNames() as $region => $name) {
            if (false === isset($countries[$region])) {
                continue;
            }

            $countryChoices[sprintf('%s (+%s)', $name, $countries[$region])] = $region;
        }

        return self::$countryChoices = array_values($countryChoices);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryOptions = $numberOptions = [
            'error_bubbling' => true,
            'required'       => $options['required'],
            'disabled'       => $options['disabled'],
        ];

        $numberOptions['attr'] = $options['number_attr'];

        $builder
            ->add('country', HiddenType::class, $countryOptions)
            ->add('number', TextType::class, $numberOptions)
            ->addViewTransformer(new PhoneNumberToArrayTransformer(self::getCountryChoices()));
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        FormUtil::addClass($view, 'phone-number');
        FormUtil::addClass($view['country'], 'country');
        FormUtil::addClass($view['number'], 'number');

        $view->vars['attr']['name'] = $view->vars['full_name'];
        $view->vars['attr']['data-type'] = $options['type'] === Types::MOBILE ? 'mobile' : 'fixed';

        if (!empty($options['country_field']) && isset($view->parent->children[$options['country_field']])) {
            $view->vars['attr']['data-watch'] = $view->parent->children[$options['country_field']]->vars['id'];
        }

        // Initial country
        if (!empty($form->get('country')->getData())) {
            return;
        }
        if (empty($country = $options['default_country'])) {
            $country = $this->userCountryGuesser->getUserCountry();
        }
        if (!empty($country)) {
            $view->children['country']->vars['value'] = $country;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'compound'        => true,
                'format'          => Formats::NATIONAL,
                'type'            => Types::FIXED_LINE,
                'invalid_message' => 'This value is not a valid phone number.',
                'by_reference'    => false,
                'error_bubbling'  => false,
                'default_country' => null,
                'country_field'   => null,
                'number_attr'     => [],
            ])
            ->setAllowedTypes('format', 'int')
            ->setAllowedTypes('type', 'int')
            ->setAllowedTypes('default_country', ['null', 'string'])
            ->setAllowedTypes('country_field', ['null', 'string'])
            ->setAllowedTypes('number_attr', 'array')
            ->setAllowedValues('format', [
                Formats::INTERNATIONAL,
                Formats::NATIONAL,
            ])
            ->setAllowedValues('type', [
                Types::FIXED_LINE,
                Types::MOBILE,
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'ekyna_phone_number';
    }
}
