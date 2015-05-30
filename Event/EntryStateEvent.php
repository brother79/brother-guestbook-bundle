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
 * An event that occurs related to updating the state 
 * of a list of guestbook entries.
 */
class EntryStateEvent extends EntriesEvent
{
    /**
     * @var integer
     */
    private $state;

    /**
     * Constructs an event.
     *
     * @param array 	$ids
     * @param integer	$state
     */
    public function __construct($ids, $state)
    {
        parent::__construct($ids);
        $this->state = $state;
    }

    /**
     * Returns the state for this event.
     *
     * @return integer $state
     */
    public function getState()
    {
        return $this->state;
    }
}
