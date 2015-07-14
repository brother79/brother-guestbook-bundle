<?php

namespace Brother\GuestbookBundle\Mailer;

use Brother\CommonBundle\Event\Events;
use Brother\CommonBundle\Event\MailEvent;
use Brother\CommonBundle\Model\Entry\EntryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Base class for the Mailer.
 */
abstract class BaseMailer implements MailerInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface 	$dispatcher
     * @param \Symfony\Component\Templating\EngineInterface                 $templating
     * @param array                                                         $config
     */
    public function __construct(EventDispatcherInterface $dispatcher, EngineInterface $templating, $config)
    {
        $this->config = $config;
        $this->dispatcher = $dispatcher;
        $this->templating = $templating;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface		$form
     * @param EntryInterface 							$entry
     *
     * @return boolean
     */
    public function sendReply(FormInterface $form, EntryInterface $entry)
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
