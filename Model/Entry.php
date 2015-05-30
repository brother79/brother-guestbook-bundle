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

/**
 * Base class for the guestbook class.
 */
 
abstract class Entry implements EntryInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     *
     */
    protected $name;

    /**
     * @var string
     *
     */
    protected $email;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var boolean
     */
    protected $state;

    /**
     * @var \DateTime
     */
    protected $created_at;

    /**
     * @var \DateTime
     */
    protected $updated_at;

    /**
     * @var boolean
     */
    protected $replied;

    /**
     * @var \DateTime
     */
    protected $replied_at;
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
	
    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }
	
    /**
     * Set state
     *
     * @param boolean $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }
	
    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set modified
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }
    

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
       if(!$this->validateDate($this->updated_at))
            $this->updated_at = null;

        return $this->updated_at;
    }
    
    /**
     * Set replied
     *
     * @param boolean $replied
     */
    public function setReplied($replied)
    {
        $this->replied = $replied;
    }

    /**
     * Get replied
     *
     * @return boolean
     */
    public function getReplied()
    {
        return $this->replied;
    }

    /**
     * Set modified
     *
     * @param \DateTime $repliedAt
     */
    public function setRepliedAt($repliedAt)
    {
        $this->replied_at = $repliedAt;
    }
    
    /**
     * Get replied_at
     *
     * @return \DateTime 
     */
    public function getRepliedAt()
    {
       if(!$this->validateDate($this->replied_at))
            $this->replied_at = null;

        return $this->replied_at;
    }
    
    /**
     * Update replied_at
     */
    public function updateRepliedAt()
    {
        $this->setReplied(1);
        $this->replied_at = new \DateTime();
    }
    
    /**
     * validate date fields
     *
     * @param mixed $date 
     * @return bool 
     */
    function validateDate($date)
    {
        if(! $date instanceof \DateTime) {
            return false;
		}
		
        list($month, $day, $year) = explode('-', $date->format('n-j-Y'));
		
        return checkdate( (int)$month, (int)$day , (int)$year );
    }
	
    /**
     * Pre persist
     */
    public function prePersist()
    {
        $this->created_at = new \DateTime();
        $this->replied = 0;
    }   
   
    /**
     * Pre update
     */
    public function preUpdate()
    {
        $this->updated_at = new \DateTime();
        if ($this->created_at == null) {
            $this->created_at = new \DateTime();
        }
    }
}
