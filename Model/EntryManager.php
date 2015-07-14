<?php

namespace Brother\GuestbookBundle\Model;

use Brother\CommonBundle\Event\EntryDeleteEvent;
use Brother\CommonBundle\Event\EntryEvent;
use Brother\CommonBundle\Event\Events;
use Brother\CommonBundle\Model\Entry\EntryInterface;
use Brother\CommonBundle\Model\Entry\ORMEntryManager;
use Brother\GuestbookBundle\Event\EntryStateEvent;
use Brother\GuestbookBundle\Pager\PagerInterface;

use Brother\QuestBundle\Entity\Entry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Base class for the guestbook manager.
 */
abstract class EntryManager extends ORMEntryManager
{

    /**
     * @var boolean
     */
    protected $autoPublish = true;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param EntityManager $em
     * @param string $class
     * @param boolean $autoPublish
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em, $class, $autoPublish)
    {
        $this->autoPublish = $autoPublish;
        parent::__construct($dispatcher, $em, $class );
    }


    /**
     * Creates an empty Entry instance
     *
     * @param integer $id
	 *
     * @return EntryInterface
     */
    public function createEntry($id = null)
    {
        $class = $this->getClass();
        $entry = new $class;
        /* @var $entry Entry */
        if (null !== $id) {
            $entry->setId($id);
        }

        $entry->setState($this->autoPublish ? 1 : 0);

        $event = new EntryEvent($entry);
        $this->dispatcher->dispatch(Events::ENTRY_CREATE, $event);

        return $entry;
    }

    /**
     * Persists a guestbook entry.
     *
     * @param EntryInterface $entry
     *
     * @return boolean
     */
    public function save(EntryInterface $entry)
    {
        // set state for new entry
        if ($this->isNew($entry))  {
            if($this->autoPublish) {
                $entry->setState(1);
            } else {
                $entry->setState(0);
            }
        }

        $event = new EntryEvent($entry);
        $this->dispatcher->dispatch(Events::ENTRY_PRE_PERSIST, $event);

        $this->doSave($entry);

        if ($event->isPropagationStopped()) {
            return false;
        }

        $this->dispatcher->dispatch(Events::ENTRY_POST_PERSIST, $event);

        return true;
    }

    /**
     * Removes a guestbook entry.
     *
     * @param EntryInterface $entry
     *
     * @return boolean
     */
    public function remove(EntryInterface $entry)
    {
        $event = new EntryEvent($entry);
        $this->dispatcher->dispatch(Events::ENTRY_PRE_REMOVE, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }

        $this->doRemove($entry);

        $this->dispatcher->dispatch(Events::ENTRY_POST_REMOVE, $event);

        return true;
    }

    /**
     * Deletes a list of guestbook entries
     *
     * @param array $ids
     *
     * @return boolean
     */
    public function delete(array $ids)
    {
        $event = new EntryDeleteEvent($ids);
        $this->dispatcher->dispatch(Events::ENTRY_PRE_DELETE, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }

        $this->doDelete($ids);

        $this->dispatcher->dispatch(Events::ENTRY_POST_DELETE, $event);

        return true;
    }

    /**
     * Update the state of a list of guestbook entries
     *
     * @param array 	$ids
     * @param integer	$state
     *
     * @return boolean
     */
    public function updateState($ids, $state)
    {
        $event = new EntryStateEvent($ids, $state);
        $this->dispatcher->dispatch(Events::ENTRY_PRE_UPDATE_STATE, $event);

        if ($event->isPropagationStopped()) {
            return false;
        }

        $this->doUpdateState($ids, $state);

        $this->dispatcher->dispatch(Events::ENTRY_POST_UPDATE_STATE, $event);

        return true;
    }

    /**
     * Get the pagination html
     *
     * @return string
     */
    public function getPaginationHtml()
    {
        $html = '';
        if(null !== $this->pager)
        {
            $html = $this->pager->getHtml();
        }

        return $html;
    }

    /**
     * Update the guestbook entry replied fields.
     *
     * @param EntryInterface 							$entry
     * @param \Symfony\Component\Form\FormInterface		$form
	 *
     * @return boolean
     */
    public function updateReplyFields(EntryInterface $entry, FormInterface $form)
    {
        $entry->updateRepliedAt();

        return $this->save($entry);
    }
   /**
     * Performs the state update of a list of guestbook entries.
     *
     * @param array 	$ids
     * @param integer   $state
     */
    abstract protected function doUpdateState($ids, $state);

}
