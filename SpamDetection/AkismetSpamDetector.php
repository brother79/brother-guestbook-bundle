<?php

/*
 * This file is part of the BrotherGuestbookBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Brother\GuestbookBundle\SpamDetection;

use Brother\GuestbookBundle\Model\EntryInterface;
use Ornicar\AkismetBundle\Akismet\AkismetInterface;

/**
 * Akismet spam detector.
 */
class AkismetSpamDetector implements SpamDetectorInterface
{
    /**
     * @var AkismetInterface
     */
    protected $akismet;

    /**
     * @param \Ornicar\AkismetBundle\Akismet\AkismetInterface $akismet
     */
    public function __construct(AkismetInterface $akismet)
    {
        $this->akismet = $akismet;
    }

    /**
     * Checks if the guestbook entry is a spam.
     *
     * @param  EntryInterface $entry
	 *
     * @return boolean
     */
    public function isSpam(EntryInterface $entry)
    {
        return $this->akismet->isSpam(array(
			'comment_author'  => $entry->getName(),
			'comment_content' => $entry->getComment()
		));
    }

}
