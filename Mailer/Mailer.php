<?php

namespace Brother\GuestbookBundle\Mailer;

use Brother\CommonBundle\Mailer\BaseMailer;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default Mailer class.
 */
class Mailer extends BaseMailer
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface   $dispatcher
     * @param \Swift_Mailer                                                 $mailer
     * @param EngineInterface                                               $templating
     * @param array                                                         $config
     */
    public function __construct(EventDispatcherInterface $dispatcher, \Swift_Mailer $mailer, EngineInterface $templating, $config)
    {
        parent::__construct($dispatcher, $mailer, $templating, $config);
    }

}
