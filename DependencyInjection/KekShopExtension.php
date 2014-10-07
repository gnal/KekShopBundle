<?php

namespace Kek\ShopBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KekShopExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->registerConfiguration($config, $container);
    }

    private function registerConfiguration($config, ContainerBuilder $container)
    {
        $container->setParameter('kek_shop.product.class', $config['product_class']);
        $container->setParameter('kek_shop.order.class', $config['order_class']);
        $container->setParameter('kek_shop.order_item.class', $config['order_item_class']);
        $container->setParameter('kek_shop.order_address.class', $config['order_address_class']);
        $container->setParameter('kek_shop.product_category.class', $config['product_category_class']);
        $container->setParameter('kek_shop.address.class', $config['address_class']);

        $container->setParameter('kek_shop.product_admin.class', $config['product_admin_class']);
        $container->setParameter('kek_shop.product_category_admin.class', $config['product_category_admin_class']);

        $container->setParameter('kek_shop.quantity_form_type_class.class', $config['quantity_form_type_class']);

        $container->setParameter('kek_shop.quantity_min', $config['quantity_min']);
        $container->setParameter('kek_shop.quantity_max', $config['quantity_max']);
    }
}
