<?php

namespace Brother\GuestbookBundle\Mailer;

use Brother\CommonBundle\Mailer\MailerEntryInterface;
use Symfony\Component\Form\FormInterface;


/**
 * Mailer Interface
 */
interface MailerInterface
{
    /**
     * @param MailerEntryInterface $comment
     */
    public function sendAdminNotification(MailerEntryInterface $comment);

    /**
     * @param \Symfony\Component\Form\FormInterface		$form
     * @param MailerEntryInterface              				$comment
     */
    public function sendReply(FormInterface $form, MailerEntryInterface $comment);

    /**
     * @param array $options
     */
    public function sendEmail(array $options);

}
