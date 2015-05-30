<?php

/*
 * This file is part of the BrotherGuestbookBundle
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Brother\GuestbookBundle\EventListener;

use Brother\GuestbookBundle\Event\Events;
use Brother\GuestbookBundle\Event\EntryEvent;
use Brother\GuestbookBundle\SpamDetection\SpamDetectorInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * A listener that checks if a guestbook entry is a spam based on a service
 * that implements SpamDetectorInterface.
 */
class SpamListener implements EventSubscriberInterface
{
    /**
     * @var SpamDetectionInterface
     */
    protected $spamDetector;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param SpamDetectorInterface                                    $detector
     * @param \Symfony\Component\HttpKernel\Log\LoggerInterface        $logger
     */
    public function __construct(SpamDetectorInterface $detector, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->spamDetector	= $detector;
    }

    /**
     * Checks if a guestbook entry is a spam
     *
     * @param EntryEvent $event
     */
    public function checkIsSpam(EntryEvent $event)
    {
        $entry = $event->getEntry();

        if ($this->spamDetector->isSpam($entry)) {
            if (null !== $this->logger) {
                $this->logger->info('This guestbook entry is marked as spam.');
            }

            $event->abortPersistence();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(Events::ENTRY_PRE_PERSIST => 'checkIsSpam');
    }
}
