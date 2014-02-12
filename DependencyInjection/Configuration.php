<?php

namespace Kek\ShopBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kek_shop');

        $rootNode
            ->children()
                ->scalarNode('product_class')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->scalarNode('order_class')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->scalarNode('order_item_class')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->scalarNode('product_category_class')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->scalarNode('address_class')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->scalarNode('quantity_min')
                    ->cannotBeEmpty()
                    ->defaultValue(1)
                ->end()
                ->scalarNode('quantity_max')
                    ->cannotBeEmpty()
                    ->defaultValue(999)
                ->end()
                ->scalarNode('product_admin_class')
                    ->cannotBeEmpty()
                    ->defaultValue('Kek\ShopBundle\Admin\ProductAdmin')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
