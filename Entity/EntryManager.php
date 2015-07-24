<?php

namespace Brother\GuestbookBundle\Entity;

use Brother\CommonBundle\Event\EntryEvent;
use Brother\CommonBundle\Event\Events;
use Brother\CommonBundle\Model\Entry\EntryInterface;
use Brother\CommonBundle\Model\Entry\ORMEntryManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Default ORM EntryManager.
 */
class EntryManager extends ORMEntryManager
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
     * {@inheritDoc}
     */
    public function getPaginatedList($offset, $limit, $criteria = array())
    {
        $queryBuilder = $this->repository->createQueryBuilder('c');

        // set state
        if(isset($criteria['state'])) {
            $queryBuilder->andWhere('c.state = :state')
				->setParameter('state', $criteria['state']);
        }

        // set replied
        if(isset($criteria['replied'])) {
            $queryBuilder->andWhere('c.replied = :replied')
				->setParameter('replied', $criteria['replied']);
        }

        // set dates
        if(isset($criteria['date_from'])) {
            $queryBuilder->andWhere('c.created_at >= :from')
				->setParameter('from', $criteria['date_from']);
        }
		
        if(isset($criteria['date_to'])) {
            $queryBuilder->andWhere('c.created_at <= :to')
				->setParameter('to', $criteria['date_to']);
        }

        // set ordering
        if(isset($criteria['order'])) {
            foreach($criteria['order'] as $ordering) {
                $queryBuilder->addOrderBy($ordering['field'], $ordering['order']);
            }
        } else  {
            $queryBuilder->orderBy('c.created_at', 'DESC');    //default ordering
        }

		if (null === $this->paginator) {
			return $queryBuilder->getQuery()->getResult();
		}
		
        return $this->makeKnpPagination($limit, array('page' => $offset, 'target' => $queryBuilder->getQuery()));
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
     * {@inheritDoc}
     */
    protected function doDelete($ids)
    {
        $this->em->createQueryBuilder()
            ->delete($this->getClass(), 'c')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute();
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
     * {@inheritDoc}
     */
    protected function doUpdateState($ids, $state)
    {
        $this->em->createQueryBuilder()
            ->update($this->getClass(), 'c')
            ->set('c.state', ':state')
            ->set('c.updatedAt', ':date' )
            ->where('c.id IN (:ids)')
            ->setParameters( array(
                'state' => $state,
                'ids' => $ids,
                'date' => new \DateTime(),
            ))
            ->getQuery()
            ->execute();
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

}
