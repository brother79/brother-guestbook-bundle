<?php

namespace Brother\GuestbookBundle\Controller;

use Brother\GuestbookBundle\Model\EntryManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Base class for the guestbook controllers.
 */
abstract class BaseController extends Controller
{
    /**
     * @var EntryManagerInterface
     */
    protected $manager = null;

    /**
     * Returns the guestbook entry manager
     *
     * @return EntryManagerInterface
     */
    public function getManager()
    {
        if (null === $this->manager) {
            $this->manager = $this->container->get('brother_guestbook.entry_manager');
        }

        return $this->manager;
    }

    /**
     * Returns the requested Form Factory service
     *
     * @param string $name
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getFormFactory($name)
    {
        return $this->container->get('brother_guestbook.form_factory.' . $name);
    }

    /**
     * Returns the requested View
     *
     * @param string    $name
     *
     * @return string
     */
    public function getView($name)
    {
        return $this->container->getParameter('brother_guestbook.view.' . $name);
    }

    /**
     * Returns the guestbook entry for a given id
     *
     * @param $id
     *
     * @return EntryInterface
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getEntry($id)
    {	
        $entry = $this->getManager()->findOneById($id);

        if (null === $entry) {
            throw new NotFoundHttpException(sprintf("Guestbook entry with id '%s' does not exists.", $id));
        }

        return $entry;
    }
   
    /**
     * Translate and set Flash bag message
     * 
     * @param string 	$msg
     * @param array 	$args
     * @param string 	$type
     */
    public function setFlashMessage($msg, $args=array(), $type='notice')
    {
        $msg = $this->get('translator')->trans($msg, $args, 'BrotherGuestbookBundle');
        $this->get('session')->getFlashBag()->add($type, $msg);
    }

    /**
     * Set Flash bag message
     * 
     * @param string $msg
     * @param string $type
     */
    public function setFlashBag($msg, $type='notice')
    {
        $this->get('session')->getFlashBag()->add($type, $msg);
    }
}
