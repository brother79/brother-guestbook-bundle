<?php

/*
 * This file is part of the BrotherGuestbookBundle package.
 *
 * (c) Yos Okus <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Brother\GuestbookBundle\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Brother\GuestbookBundle\Model\EntryInterface;
use Brother\GuestbookBundle\Model\EntryManager as AbstractEntryManager;

/**
 * Default EntryManager ODM.
 */
class EntryManager extends AbstractEntryManager
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var Doctrine\ODM\MongoDB\DocumentRepository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface 	$dispatcher
     * @param DocumentManager 												$dm
     * @param string          												$class
     * @param boolean  		  												$autoPublish
     */
    public function __construct(EventDispatcherInterface $dispatcher, DocumentManager $dm, $class, $autoPublish)
    {
        parent::__construct($dispatcher, $dm->getClassMetadata($class)->name, $autoPublish);

        $this->dm = $dm;
        $this->repository = $dm->getRepository($class);
    }

    /**
     * @return Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->dm;
    }

    /**
     * @return Doctrine\ODM\MongoDB\DocumentRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritDoc}
     */
    public function findEntryBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findEntriesBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findAllEntries()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNew(EntryInterface $entry)
    {
        return !$this->dm->getUnitOfWork()->isInIdentityMap($entry);
    }

    /**
     * {@inheritDoc}
     */
    protected function doSave(EntryInterface $entry)
    {
        $this->dm->persist($entry);
        $this->dm->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function doRemove(EntryInterface $entry)
    {
        $this->dm->remove($entry);
        $this->dm->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getPaginatedList($offset, $limit, $criteria = array())
    {
        $queryBuilder = $this->repository->createQueryBuilder();
         
        // set state
        if(isset($criteria['state'])) {
            $queryBuilder->field('state')->equals((bool)$criteria['state']);
        }

        // set replied
        if(isset($criteria['replied'])) {
            $queryBuilder->field('replied')->equals((bool)$criteria['replied']);
        }

        // set dates
        if(isset($criteria['date_from'])) {
            $queryBuilder->field('created_at')->gte($criteria['date_from']);
        }

        if(isset($criteria['date_to'])) {
            $queryBuilder->field('created_at')->lte($criteria['date_from']);
        }

        // set ordering
        $sorting = array();
        if(isset($criteria['order'])) {
            foreach($criteria['order'] as $ordering) {
                $sorting[$ordering['field']] = $ordering['order'];
            }
        } else  {
            $sorting['created_at'] = 'DESC';	//default ordering
        }

        $queryBuilder->sort($sorting);

        if (null === $this->pager) {
            return $queryBuilder->getQuery()->execute();
        }

        return $this->pager->getList($queryBuilder, $offset, $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function doDelete($ids)
    {
        $this->repository->createQueryBuilder()
            ->remove()
            ->field('id')->in($ids)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritDoc}
     */
    protected function doUpdateState($ids, $state)
    {
        $this->repository->createQueryBuilder()
            ->update()
            ->multiple(true)
            ->field('state')->set((int)$state)
            ->field('updatedAt')->set(new \DateTime())
            ->field('id')->in($ids)
            ->getQuery()
            ->execute();
    }
}
