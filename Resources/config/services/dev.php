<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\UiBundle\Command\CountryFlagsCommand;
use Ekyna\Bundle\UiBundle\Command\FaIconsCommand;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        // Country flags command
        ->set('ekyna_ui.command.country_flags', CountryFlagsCommand::class)
            ->tag('console.command')

        // FA Icons command
        ->set('ekyna_ui.command.fa_icons', FaIconsCommand::class)
            ->args([
                param('kernel.environment')
            ])
            ->tag('console.command')
    ;
};
