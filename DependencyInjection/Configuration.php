<?php

namespace Brother\GuestbookBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('brother_guestbook');

        $treeBuilder->root('brother_guestbook')
            ->children()
            ->scalarNode('db_driver')->defaultValue('orm')->end()
            ->integerNode('entry_per_page')->min(1)->defaultValue(25)->end()
            ->booleanNode('auto_publish')->defaultTrue()->end()
            ->booleanNode('notify_admin')->defaultFalse()->end()
            ->scalarNode('date_format')->defaultValue('d/m/Y H:i:s')->end()
            ->scalarNode('user_class')->defaultValue('AppBundle\Entity\User\User')->end()
            ->arrayNode('mailer')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('admin_email')->defaultValue('admin@localhost.com')->end()
            ->scalarNode('sender_email')->defaultValue('admin@localhost.com')->end()
            ->scalarNode('email_title')->defaultValue('New guestbook entry from {name}')->end()
            ->end()
            ->end()
            ->arrayNode('form')->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('entry')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('name')->cannotBeEmpty()->defaultValue('brother_guestbook_entry')->end()
            ->scalarNode('type')->cannotBeEmpty()->defaultValue('brother_guestbook_entry')->end()
            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Brother\GuestbookBundle\Form\EntryType')->end()
            ->end()
            ->end()
            ->arrayNode('edit')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('name')->cannotBeEmpty()->defaultValue('brother_guestbook_entry_edit')->end()
            ->scalarNode('type')->cannotBeEmpty()->defaultValue('brother_guestbook_entry_edit')->end()
            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Brother\GuestbookBundle\Form\EntryEditType')->end()
            ->end()
            ->end()
            ->arrayNode('reply')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('name')->cannotBeEmpty()->defaultValue('brother_guestbook_entry_reply')->end()
            ->scalarNode('type')->cannotBeEmpty()->defaultValue('brother_guestbook_entry_reply')->end()
            ->scalarNode('class')->cannotBeEmpty()->defaultValue('Brother\GuestbookBundle\Form\EntryReplyType')->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('class')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('mailer')->cannotBeEmpty()->defaultValue('Brother\GuestbookBundle\Mailer\Mailer')->end()
            ->scalarNode('model')->cannotBeEmpty()->end()
            ->scalarNode('manager')->cannotBeEmpty()->end()
            ->scalarNode('pager')->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->arrayNode('service')->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('pager')->cannotBeEmpty()->end()
            ->scalarNode('mailer')->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
