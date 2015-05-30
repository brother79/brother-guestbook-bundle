<?php

/*
 * This file is part of the BrotherGuestbookBundle package
 *
 * (c) Yos Okus <yos.okusanya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brother\GuestbookBundle\Mailer;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default Mailer class.
 */
class Mailer extends BaseMailer
{
    protected $mailer;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface   $dispatcher
     * @param \Swift_Mailer                                                 $mailer
     * @param EngineInterface                                               $templating
     * @param array                                                         $config
     */
    public function __construct(EventDispatcherInterface $dispatcher, \Swift_Mailer $mailer, EngineInterface $templating, $config)
    {
        parent::__construct($dispatcher, $templating, $config);

        $this->mailer = $mailer;
    }


    /**
     * @param array $options
     */
    public function sendEmail(array $options)
    {
        if (null !== $this->mailer) {
            $message = \Swift_Message::newInstance()
                ->setSubject($options['subject'])
                ->setFrom($options['from'])
                ->setTo($options['to'])
                ->setBody($options['body']);

            if (isset($options['cc'])) {
                $message->setCc($options['body']);
            }

            $this->mailer->send($message);
        }
    }
}
