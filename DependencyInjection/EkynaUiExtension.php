<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\DependencyInjection;

use Ekyna\Bundle\ResourceBundle\DependencyInjection\PrependBundleConfigTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

use function array_keys;
use function in_array;

/**
 * Class EkynaUiExtension
 * @package Ekyna\Bundle\UiBundle\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaUiExtension extends Extension implements PrependExtensionInterface
{
    use PrependBundleConfigTrait;

    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/form.php');
        $loader->load('services.php');

        if ('dev' === $container->getParameter('kernel.environment')) {
            $loader->load('services/dev.php');
        }

        $this->configureUi($config, $container);
        $this->configureModal($config, $container);
        $this->configureTinymce($config, $container);
    }

    /**
     * Configures the UI services.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function configureUi(array $config, ContainerBuilder $container): void
    {
        if (!in_array('bundles/ekynaui/css/form.css', $config['stylesheets']['forms'])) {
            $config['stylesheets']['forms'][] = 'bundles/ekynaui/css/form.css';
        }

        $container
            ->getDefinition('ekyna_ui.renderer')
            ->replaceArgument(1, $config);

        $container
            ->getDefinition('ekyna_ui.form_type.color_picker')
            ->replaceArgument(0, $config['colors']);
    }

    /**
     * Configures the Tinymce services.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function configureModal(array $config, ContainerBuilder $container): void
    {
        $container
            ->getDefinition('ekyna_ui.modal.renderer')
            ->replaceArgument(3, $config['modal']);
    }

    /**
     * Configures the Tinymce services.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function configureTinymce(array $config, ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');
        $tinymceCfgBuilder = new TinymceConfigBuilder();
        $tinymceConfig = $tinymceCfgBuilder->build($config, $bundles);

        $container
            ->getDefinition('ekyna_ui.controller.tinymce')
            ->replaceArgument(3, $tinymceConfig);

        $container
            ->getDefinition('ekyna_ui.form_type.tinymce')
            ->replaceArgument(0, array_keys($tinymceConfig['theme']));
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->prependBundleConfigFiles($container);
    }
}
