<?php

namespace Brother\GuestbookBundle\Event;

use Brother\CommonBundle\Event\EntriesEvent;

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
