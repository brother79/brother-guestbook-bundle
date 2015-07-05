<?php

/*
 * This file is part of the BrotherGuestbookBundle package.
 *
 * (c) Yos Okusanya <yos.okusanya@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace Brother\GuestbookBundle\Model;

use Symfony\Component\Form\FormInterface;

/**
 * Interface to be implemented by the guestbook manager.
 */
interface EntryManagerInterface
{
    /**
     * @param string $id
     *
     * @return EntryInterface
     */
    public function findOneById($id);

    /**
     * Finds a guestbook entry by the given criteria
     *
     * @param array $criteria
     *
     * @return EntryInterface
     */
    public function findOneBy(array $criteria);

    /**
     * Finds guestbook entries by the given criteria
     *
     * @param array $criteria
     *
     * @return array of EntryInterface
     */
    public function findBy(array $criteria);

    /**
     * Creates an empty guestbook entry instance
     *
     * @param integer $id
     *
     * @return EntryInterface
     */
    public function createEntry($id = null);

    /**
     * Saves a guestbook entry
     *
     * @param EntryInterface $entry
     */
    public function save(EntryInterface $entry);

    /**
     * Returns the guestbook fully qualified class name
     *
     * @return string
     */
    public function getClass();

    /**
     * Deletes a guestbook entry
     *
     * @param EntryInterface $entry
     */
    public function remove(EntryInterface $entry);

    /**
     * Deletes a list of guestbook entries
     *
     * @param array $ids
     */
    public function delete(array $ids);

    /**
     * Update the state of a list of guestbook entries
     *
     * @param array		$ids
     * @param integer	$state
     */
    public function updateState($ids, $state);

    /**
     * Update the guestbook entry replied fields
     *
     * @param EntryInterface 				        $entry
     * @param \Symfony\Component\Form\FormInterface 	$form
     */
    public function updateReplyFields(EntryInterface $entry, FormInterface $form);

    /**
     * Finds entries by the given criteria
     * and from the query offset.
     *
     * @param integer 	$offset
     * @param integer	$limit
     * @param array 	$criteria
     *
     * @return array of EntryInterface
     */
    public function getPaginatedList($offset, $limit, $criteria = array());

    /**
     * Gets the pagination html
     *
     * @return string
     */
    public function getPaginationHtml();

}
