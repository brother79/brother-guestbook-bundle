<?php

namespace Brother\GuestbookBundle\DependencyInjection;

use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BrotherGuestbookExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all Bundles
        $bundles = $container->getParameter('kernel.bundles');
        $brotherConfig = array();

        // get the BrotherGuestbook configuration
        $configs = $container->getExtensionConfig($this->getAlias());
        $guestbookConfig = $this->processConfiguration(new Configuration(), $configs);

        // enable spam detection if AkismetBundle is registered
        // else disable spam detection
        // can be overridden by setting the brother_guestbook.spam_detection.enable config
        $brotherConfig['spam_detection'] = isset($bundles['AkismetBundle']) ? true : false;

            if ( 'orm' == $guestbookConfig['db_driver']) {
            $brotherConfig['class']['pager'] = 'Brother\GuestbookBundle\Pager\DefaultORM';
            } else {
            $brotherConfig['class']['pager'] = 'Brother\GuestbookBundle\Pager\DefaultMongodb';
        }

        // add the BrotherGuestbookBundle configurations
        // all options can be overridden in the app/config/config.yml file
        $container->prependExtensionConfig('brother_guestbook', $brotherConfig);
    }

    /**
     * {@inheritDoc}
     */	
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if (!in_array(strtolower($config['db_driver']), array('mongodb', 'orm'))) {
            throw new \InvalidArgumentException(sprintf('Invalid db driver "%s".', $config['db_driver']));
        }
        $loader->load(sprintf('%s.yml', $config['db_driver']));

        $loader->load('form.yml');

        // core config
        $container->setParameter('brother_guestbook.auto_publish', $config['auto_publish']);
        $container->setParameter('brother_guestbook.entry_per_page', $config['entry_per_page']);
        $container->setParameter('brother_guestbook.date_format', $config['date_format']);
        $container->setParameter('brother_guestbook.notify_admin', $config['notify_admin']);

        // mailer
        $container->setParameter('brother_guestbook.mailer.class', $config['class']['mailer']);
        $container->setParameter('brother_guestbook.mailer.email_title', $config['mailer']['email_title']);
        $container->setParameter('brother_guestbook.mailer.admin_email', $config['mailer']['admin_email']);
        $container->setParameter('brother_guestbook.mailer.sender_email', $config['mailer']['sender_email']);

        // forms
        $container->setParameter('brother_guestbook.form.entry.name', $config['form']['entry']['name']);
        $container->setParameter('brother_guestbook.form.entry.type', $config['form']['entry']['type']);
        $container->setParameter('brother_guestbook.form.entry.class', $config['form']['entry']['class']);

        $container->setParameter('brother_guestbook.form.edit.name', $config['form']['edit']['name']);
        $container->setParameter('brother_guestbook.form.edit.type', $config['form']['edit']['type']);
        $container->setParameter('brother_guestbook.form.edit.class', $config['form']['edit']['class']);

        $container->setParameter('brother_guestbook.form.reply.name', $config['form']['reply']['name']);
        $container->setParameter('brother_guestbook.form.reply.type', $config['form']['reply']['type']);
        $container->setParameter('brother_guestbook.form.reply.class', $config['form']['reply']['class']);

        // views
        $container->setParameter('brother_guestbook.view.frontend.list', $config['view']['frontend']['list']);
        $container->setParameter('brother_guestbook.view.frontend.new', $config['view']['frontend']['new']);
        $container->setParameter('brother_guestbook.view.mail.notify', $config['view']['mail']['notify']);

        // set model class
        if (isset($config['class']['model'])) {
            $container->setParameter('brother_guestbook.model.entry.class', $config['class']['model']);
        }

        // set manager class
        if (isset($config['class']['manager'])) {
            $container->setParameter('brother_guestbook.manager.entry.class', $config['class']['manager']);
        }

        // set pager class
        if (isset($config['class']['pager'])) {
            $container->setParameter('brother_guestbook.pager.class', $config['class']['pager']);
        }

        // load custom mailer service if set
        if (isset($config['service']['mailer'])) {
            $container->setAlias('brother_guestbook.mailer', $config['service']['mailer']);
        }

        // load custom pager service if set  else load the default pager
        if (isset($config['service']['pager'])) {
            $container->setAlias('brother_guestbook.pager', $config['service']['pager']);
        } else {
            $container->setAlias('brother_guestbook.pager', 'brother_guestbook.pager.default');
        }

        // spam detection
        $container->setParameter('brother_guestbook.enable_spam_detection', $config['spam_detection']);

        if ($config['spam_detection']) {
            // load external spam detector if set else load default
            if (isset($config['service']['spam_detector'])) {
                $container->setAlias('brother_guestbook.spam_detector', $config['service']['spam_detector']);
            } else {
                $loader->load('spam_detection.xml');
                $container->setAlias('brother_guestbook.spam_detector', 'brother_guestbook.spam_detector.akismet');
            }
        }
        $this->registerDoctrineMapping($config);

    }

    public function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();

        $collector->addAssociation('Brother\GuestbookBundle\Entity\Entry', 'mapManyToOne', array(
            'fieldName'     => 'user',
            'targetEntity'  => $config['user_class'],
            'cascade'       => array(
                'persist',
            ),
            'mappedBy'      => NULL,
            'joinColumns'   =>  array(
                array(
                    'name'  => 'user_id',
                    'referencedColumnName' => 'id',
                ),
            ),
            'orphanRemoval' => false,
        ));

    }

}
