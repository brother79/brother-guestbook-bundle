<?php

namespace Brother\GuestbookBundle\Mailer;

use Brother\CommonBundle\Model\Entry\EntryInterface;
use Symfony\Component\Form\FormInterface;


/**
 * Mailer Interface
 */
interface MailerInterface
{
    /**
     * @param EntryInterface $comment
     */
    public function sendAdminNotification(EntryInterface $comment);

    /**
     * @param \Symfony\Component\Form\FormInterface		$form
     * @param EntryInterface              				$comment
     */
    public function sendReply(FormInterface $form, EntryInterface $comment);

    /**
     * @param array $options
     */
    public function sendEmail(array $options);

}
