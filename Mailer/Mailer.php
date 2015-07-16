<?php

namespace Brother\GuestbookBundle\Mailer;

use Brother\CommonBundle\Event\Events;
use Brother\CommonBundle\Event\MailEvent;
use Brother\CommonBundle\Mailer\BaseMailer;
use Brother\CommonBundle\Mailer\MailerEntryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default Mailer class.
 */
class Mailer extends BaseMailer implements MailerInterface
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

    /**
     * @param \Symfony\Component\Form\FormInterface		$form
     * @param MailerEntryInterface 							$entry
     *
     * @return boolean
     */
    public function sendReply(FormInterface $form, MailerEntryInterface $entry)
    {
        $mailOptions = array();
        $mailOptions['from'] = $form->get('senderEmail')->getData();
        $mailOptions['to'] = $form->get('email')->getData();
        $mailOptions['body'] = $form->get('message')->getData();
        $mailOptions['subject'] = $form->get('title')->getData();

        $event = new MailEvent($entry, $mailOptions);
        $this->dispatcher->dispatch(Events::ENTRY_PRE_REPLY, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }

        $this->sendEmail($mailOptions);
        $this->dispatcher->dispatch(Events::ENTRY_POST_REPLY, $event);

        return true;
    }

}
