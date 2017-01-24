<?php

namespace MediaMonks\SonataMediaBundle\DependencyInjection;

use MediaMonks\SonataMediaBundle\MediaMonksSonataMediaBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(MediaMonksSonataMediaBundle::BUNDLE_CONFIG_NAME);

        $this->addFilesystem($rootNode);
        $this->addRedirectUrl($rootNode);
        $this->addRedirectCacheTtl($rootNode);
        $this->addProviders($rootNode);
        $this->addGlideConfig($rootNode);
        $this->addDefaultImageParameters($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addFilesystem(ArrayNodeDefinition $node)
    {
        $node->children()
            ->scalarNode('filesystem_private')
            ->end();

        $node->children()
            ->scalarNode('filesystem_public')
            ->defaultNull()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addRedirectUrl(ArrayNodeDefinition $node)
    {
        $node->children()
            ->scalarNode('redirect_url')
            ->defaultNull()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addRedirectCacheTtl(ArrayNodeDefinition $node)
    {
        $node->children()
            ->scalarNode('redirect_cache_ttl')
            ->defaultValue(60 * 60 * 24 * 90)// 90 days
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addProviders(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('providers')
            ->defaultValue(
                [
                    'mediamonks.sonata_media.provider.file',
                    'mediamonks.sonata_media.provider.image',
                    'mediamonks.sonata_media.provider.youtube',
                    'mediamonks.sonata_media.provider.soundcloud'
                ]
            )
            ->prototype('scalar')->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addGlideConfig(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('glide')
            ->prototype('scalar')->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addDefaultImageParameters(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('default_image_parameters')
            ->prototype('scalar')->end()
            ->end();
    }
}
