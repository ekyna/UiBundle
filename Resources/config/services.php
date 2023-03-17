<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UiBundle\Controller\FormController;
use Ekyna\Bundle\UiBundle\Controller\TinymceController;
use Ekyna\Bundle\UiBundle\Service\BootstrapHelper;
use Ekyna\Bundle\UiBundle\Service\FlashHelper;
use Ekyna\Bundle\UiBundle\Service\Geo\UserCountryGuesser;
use Ekyna\Bundle\UiBundle\Service\IntlHelper;
use Ekyna\Bundle\UiBundle\Service\Menu\UriVoter;
use Ekyna\Bundle\UiBundle\Service\Modal\ModalRenderer;
use Ekyna\Bundle\UiBundle\Service\TwigHelper;
use Ekyna\Bundle\UiBundle\Service\UiRenderer;
use Ekyna\Bundle\UiBundle\Twig\DecimalExtension;
use Ekyna\Bundle\UiBundle\Twig\FormExtension;
use Ekyna\Bundle\UiBundle\Twig\UiExtension;
use Ekyna\Bundle\UiBundle\Twig\UtilsExtension;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    // Form controller
    $services
        ->set('ekyna_ui.controller.form', FormController::class)
        ->args([
            abstract_arg('The form JS configuration'),
        ])
        ->alias(FormController::class, 'ekyna_ui.controller.form')->public();

    // Tinymce controller
    $services
        ->set('ekyna_ui.controller.tinymce', TinymceController::class)
        ->args([
            service('security.authorization_checker'),
            service('assets.packages'),
            service('local_tinymce_filesystem'),
            abstract_arg('The tinymce configuration'),
        ])
        ->alias(TinymceController::class, 'ekyna_ui.controller.tinymce')->public();

    // User country guesser
    $services
        ->set('ekyna_ui.geo.user_country_guesser', UserCountryGuesser::class)
        ->args([
            service('request_stack'),
        ]);

    // KnpMenu matcher voter
    $services
        ->set('ekyna_ui.menu.uri_voter', UriVoter::class)
        ->args([
            service('request_stack'),
        ])
        ->tag('knp_menu.voter');

    // Modal renderer
    $services
        ->set('ekyna_ui.modal.renderer', ModalRenderer::class)
        ->args([
            service('twig'),
            service('translator'),
            service('event_dispatcher'),
            abstract_arg('The modal configuration'),
        ])
        ->tag('twig.runtime');

    // Ui renderer
    $services
        ->set('ekyna_ui.renderer', UiRenderer::class)
        ->args([
            service('twig'),
            abstract_arg('The UI configuration'),
        ])
        ->tag('twig.runtime');

    // Bootstrap helper
    $services
        ->set('ekyna_ui.helper.bootstrap', BootstrapHelper::class)
        ->tag('twig.runtime');

    // Flash helper
    $services
        ->set('ekyna_ui.helper.flash', FlashHelper::class)
        ->args([
            service('request_stack'),
        ])
        ->alias(FlashHelper::class, 'ekyna_ui.helper.flash');

    // Twig helper
    $services
        ->set('ekyna_ui.helper.twig', TwigHelper::class)
        ->args([
            service('twig.loader'),
        ]);

    // Ui helper
    $services
        ->set('ekyna_ui.helper.intl', IntlHelper::class)
        ->args([
            service('request_stack'),
            service('translator'),
            param('kernel.default_locale'),
        ])
        ->tag('twig.runtime');

    // Decimal (Intl) twig extension
    $services
        ->set('ekyna_ui.twig.extension.decimal', DecimalExtension::class)
        ->args([
            service('twig.extension.intl'),
        ])
        ->tag('twig.extension', ['priority' => -1024]);

    // Form twig extension
    $services
        ->set('ekyna_ui.twig.extension.form', FormExtension::class)
        ->tag('twig.extension');

    // UI twig extension
    $services
        ->set('ekyna_ui.twig.extension.ui', UiExtension::class)
        ->tag('twig.extension');

    // Utils twig extension
    $services
        ->set('ekyna_ui.twig.extension.utils', UtilsExtension::class)
        ->tag('twig.extension');
};
