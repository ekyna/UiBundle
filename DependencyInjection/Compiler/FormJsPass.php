<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\DependencyInjection\Compiler;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FormJsPass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormJsPass implements CompilerPassInterface
{
    private const FORM_JS_TAG = 'form.js';


    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        // TODO configuration / extension parameter
        $formJs = ['.file-picker' => ['ekyna-form/file-picker']];

        foreach ($container->findTaggedServiceIds(self::FORM_JS_TAG, true) as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                if (!array_key_exists('selector', $attributes)) {
                    throw new InvalidArgumentException(
                        sprintf('The "selector" attributes is missing for tag "form.js" of service "%s"', $serviceId)
                    );
                }
                if (!array_key_exists('path', $attributes)) {
                    throw new InvalidArgumentException(
                        sprintf('The "path" attributes is missing for tag "form.js" of service "%s"', $serviceId)
                    );
                }

                if (!isset($formJs[$attributes['selector']])) {
                    $formJs[$attributes['selector']] = [];
                }

                $formJs[$attributes['selector']][] = $attributes['path'];
            }
        }

        foreach ($formJs as &$paths) {
            $paths = array_unique($paths);
        }

        $container
            ->getDefinition('ekyna_ui.controller.form')
            ->replaceArgument(0, $formJs);
    }
}
