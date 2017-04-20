<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ekyna\Bundle\UiBundle\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('ekyna_ui');

        $root = $builder->getRootNode();
        $root
            ->children()
                ->scalarNode('controls_template')->defaultValue('@EkynaUi/controls.html.twig')->end()
                ->scalarNode('no_image_path')->defaultValue('/bundles/ekynaui/img/new-image.gif')->end()
            ->end()
        ;

        $this->addModalSection($root);
        $this->addColorsSection($root);
        $this->addTinymceSection($root);
        $this->addStylesheetsSection($root);

        return $builder;
    }

    /**
     * Adds the `modal` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addModalSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('modal')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')
                            ->cannotBeEmpty()
                            ->defaultValue('@EkynaUi/Modal/modal.xml.twig')
                        ->end()
                        ->scalarNode('charset')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.charset%')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `colors` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addColorsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('colors')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()
                        ->cannotBeEmpty()
                        ->validate()
                            ->ifTrue(function ($value) {
                                return !preg_match('~^[A-Fa-f0-9]{6}|[A-Fa-f0-9]{3}$~', $value);
                            })
                            ->thenInvalid('Invalid color.')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `tinymce` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addTinymceSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('tinymce')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('base_formats')
                            ->values([null, 'default', 'bootstrap'])
                            ->defaultNull()
                        ->end()
                        ->variableNode('custom_formats')
                            ->defaultNull()
                        ->end()
                        ->variableNode('theme')
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `stylesheets` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addStylesheetsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('stylesheets')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('contents')
                            ->treatNullLike([])
                            ->defaultValue([])
                            ->scalarPrototype()->cannotBeEmpty()->end()
                        ->end()
                        ->arrayNode('forms')
                            ->treatNullLike([])
                            ->defaultValue([])
                            ->scalarPrototype()->cannotBeEmpty()->end()
                        ->end()
                        ->arrayNode('fonts')
                            ->treatNullLike([])
                            ->defaultValue([])
                            ->scalarPrototype()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
