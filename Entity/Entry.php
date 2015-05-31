<?php

namespace Brother\GuestbookBundle\Entity;

use Brother\GuestbookBundle\Model\Entry as AbstractEntry;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Default ORM Entry Entity.
 */
class Entry extends AbstractEntry
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     * @Assert\NotBlank()
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
     * @var boolean
     */
    protected $replied;

    /**
     * @var \DateTime
     */
    protected $created_at;

    /**
     * @var \DateTime
     */
    protected $updated_at;

    /**
     * @var \DateTime
     */
    protected $replied_at;

    function __construct()
    {
        $this->created_at = new \DateTime();
    }


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
     * @return Entry
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * @return Entry
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
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
     * @return Entry
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
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

    public function getAnnounce()
    {
        if (mb_strlen($this->comment, 'utf-8')>100) {
            return mb_substr($this->comment, 0, 60, 'utf-8') . ' ... ... ... ' .
             mb_substr($this->comment, mb_strlen($this->comment, 'utf-8')-20, 30, 'utf-8');

        } else {
            return $this->comment;
        }
        return mb_substr($this->comment, 0, 100, 'utf-8');
    }

    /**
     * Set state
     *
     * @param boolean $state
     * @return Entry
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
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
     * Set replied
     *
     * @param boolean $replied
     * @return Entry
     */
    public function setReplied($replied)
    {
        $this->replied = $replied;

        return $this;
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Entry
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Entry
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set replied_at
     *
     * @param \DateTime $repliedAt
     * @return Entry
     */
    public function setRepliedAt($repliedAt)
    {
        $this->replied_at = $repliedAt;

        return $this;
    }

    /**
     * Get replied_at
     *
     * @return \DateTime 
     */
    public function getRepliedAt()
    {
        return $this->replied_at;
    }

    /**
     */
    public function prePersist()
    {
        parent::prePersist();
    }

    /**
     */
    public function preUpdate()
    {
        parent::preUpdate();
    }
    /**
     * @var string
     */
    private $profession;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;


    /**
     * Set profession
     *
     * @param string $profession
     * @return Entry
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession()
    {
        return $this->profession ? $this->profession : ($this->user ? $this->user->getProfession() : null);
    }

    /**
     * Set user
     *
     * @param \Brother\GuestbookBundle\Entity\%sonata.user.admin.user.class% $user
     * @return Entry
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function hasImage()
    {
        return $this->getUser() && $this->getUser()->getImage();
    }

    public function getImage()
    {
        return $this->hasImage() ? $this->getUser()->getImage() : null;
    }
}
