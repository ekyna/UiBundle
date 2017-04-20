<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UiBundle\Form\Extension\ButtonTypeExtension;
use Ekyna\Bundle\UiBundle\Form\Extension\DateTimeTypeExtension;
use Ekyna\Bundle\UiBundle\Form\Extension\EntityTypeExtension;
use Ekyna\Bundle\UiBundle\Form\Extension\FormTypeRedirectExtension;
use Ekyna\Bundle\UiBundle\Form\Extension\InputGroupButtonExtension;
use Ekyna\Bundle\UiBundle\Form\Extension\Select2Extension;
use Ekyna\Bundle\UiBundle\Form\Extension\StaticControlExtension;
use Ekyna\Bundle\UiBundle\Form\Type\CollectionType;
use Ekyna\Bundle\UiBundle\Form\Type\ColorPickerType;
use Ekyna\Bundle\UiBundle\Form\Type\FAIconChoiceType;
use Ekyna\Bundle\UiBundle\Form\Type\PhoneNumberType;
use Ekyna\Bundle\UiBundle\Form\Type\TinymceType;
use Ekyna\Bundle\UiBundle\Form\Type\UploadType;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // TODO normalize js selector / path (ekyna- ...)

        // -------------- Extensions --------------

        // Button type extension
        ->set('ekyna_ui.form_extension.button', ButtonTypeExtension::class)
            ->tag('form.type_extension')

        // DateTime type extension
        ->set('ekyna_ui.form_extension.datetime', DateTimeTypeExtension::class)
            ->tag('form.type_extension')

        // Entity type extension
        ->set('ekyna_ui.form_extension.entity', EntityTypeExtension::class)
            ->tag('form.type_extension')
            ->tag('form.js', ['selector' => '.entity-widget', 'path' => 'ekyna-form/entity'])

        // Form type redirection extension
        ->set('ekyna_ui.form_extension.redirection', FormTypeRedirectExtension::class)
            ->args([
                service('request_stack'),
            ])
            ->tag('form.type_extension')

        // Input group button extension
        ->set('ekyna_ui.form_extension.input_group_button', InputGroupButtonExtension::class)
            ->tag('form.type_extension')

        // Select2 extension
        ->set('ekyna_ui.form_extension.select2', Select2Extension::class)
            ->tag('form.type_extension')

        // Static control extension
        ->set('ekyna_ui.form_extension.static_control', StaticControlExtension::class)
            ->tag('form.type_extension')

        // -------------- Types --------------

        // Collection type
        ->set('ekyna_ui.form_type.collection', CollectionType::class)
            ->tag('form.type')
            ->tag('form.js', ['selector' => '.ekyna-collection', 'path' => 'ekyna-form/collection'])

        // Color picker type
        ->set('ekyna_ui.form_type.color_picker', ColorPickerType::class)
            ->args([
                abstract_arg('The configured colors'),
            ])
            ->tag('form.type')
            ->tag('form.js', ['selector' => '.form-color-picker', 'path' => 'ekyna-form/color'])

        // FA icon choice type
        ->set('ekyna_ui.form_type.fa_icon_choice', FAIconChoiceType::class)
            ->tag('form.type')
            ->tag('form.js', ['selector' => '.fa-icon-choice', 'path' => 'ekyna-form/fa-icon-choice'])

        // Phone number type
        ->set('ekyna_ui.form_type.phone_number', PhoneNumberType::class)
            ->args([
                service('ekyna_ui.geo.user_country_guesser'),
            ])
            ->tag('form.type')
            ->tag('form.js', ['selector' => '.phone-number', 'path' => 'ekyna-form/phone-number'])

        // Tinymce type
        ->set('ekyna_ui.form_type.tinymce', TinymceType::class)
            ->args([
                abstract_arg('The configured tinymce themes names'),
            ])
            ->tag('form.type')
            ->tag('form.js', ['selector' => '.tinymce', 'path' => 'ekyna-form/tinymce'])

        // Upload type
        ->set('ekyna_ui.form_type.upload', UploadType::class)
            ->tag('form.type')
            ->tag('form.js', ['selector' => '.upload-widget', 'path' => 'ekyna-form/upload'])
    ;
};
