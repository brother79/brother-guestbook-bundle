<?php

namespace Brother\GuestbookBundle\Entity;

use Brother\GuestbookBundle\Model\EntryManager as AbstractEntryManager;

/**
 * Default ORM EntryManager.
 */
class EntryManager extends AbstractEntryManager
{


    public function getLast()
    {
        $entries = $this->repository->findBy(array(),array('created_at' => 'desc'), 1);
        if (count($entries)) {
            return $entries[0];
        }
        return null;
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

		if (null === $this->pager) {
			return $queryBuilder->getQuery()->getResult();
		}
		
        return $this->pager->getList($queryBuilder, $offset, $limit);
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

}
