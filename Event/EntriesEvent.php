<?php

/*
 * This file is part of the BrotherGuestbookBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Brother\GuestbookBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Base class for an event that occurs related to performing 
 * a batch operation on a list of guestbook entries.
 */
class EntriesEvent extends Event
{
    private $ids;

    /**
     * Constructs an event.
     *
     * @param array $ids
     */
    public function __construct($ids)
    {
        $this->ids = $ids;
    }
	
    /**
     * Returns the entry ids for this event.
     *
     * @return array
     */
    public function getIds()
    {
        return $this->ids;
    }
}
