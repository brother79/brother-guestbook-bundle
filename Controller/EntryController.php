<?php

namespace Brother\GuestbookBundle\Controller;

use Brother\CommonBundle\AppTools;
use Brother\CommonBundle\Controller\BaseController;
use Brother\CommonBundle\Model\BaseApi;
use Brother\CommonBundle\Model\Entry\EntryInterface;
use Brother\GuestbookBundle\Form\EntryType;
use Brother\GuestbookBundle\Model\EntryManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Brother\GuestbookBundle\Entity\Entry;
use Brother\GuestbookBundle\Form\GuestbookType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Entry controller.
 *
 */
class EntryController extends BaseController
{
    /**
     * @var EntryManagerInterface
     */
    protected $manager = null;

    /**
     * Returns the guestbook entry manager
     *
     * @return EntryManagerInterface | \Brother\GuestbookBundle\Entity\EntryManager
     */
    private function getManager()
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
     * Shows the entries.
     *
     * @param int $page	query offset
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page=1)
    {
        $manager = $this->getManager();
        $limit = $this->container->getParameter('brother_guestbook.entry_per_page');
        $entries = $manager->getPaginatedList($page, $limit, array('state'=>1));
        $pagerHtml = $manager->getPaginationHtml();

        $form = $this->getFormFactory('entry');

        return $this->render('BrotherGuestbookBundle:Entry:index.html.twig', array(
                'entries'=>$entries,
                'form' => $form->createView(),
                'pagination_html' => $pagerHtml,
                'date_format' => $this->container->getParameter('brother_guestbook.date_format')
            )
        );

    }

    /**
     * Creates a new Guestbook entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = $this->getManager()->createEntry();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $result = new BaseApi();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            // notify admin
            if ($this->container->getParameter('brother_guestbook.notify_admin')) {
                $this->get('brother_guestbook.mailer')->sendAdminNotification($entity);
            }
            return $this->ajaxResponse($result->addMessage('Ваш отзыв получен. Вы можете оставить ещё один.')
                ->addRenderDom('#guestbook_new_dialog', array('modal' => 'hide'))
                ->result());
        }
        $result->setErrors(AppTools::getFormErrors($form));
        return $this->ajaxResponse($result->result());
    }

    /**
     * Creates a form to create a Entry entity.
     *
     * @param Entry $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Entry $entity)
    {
        $form = $this->createForm(new EntryType(get_class($entity)), $entity, array(
            'action' => $this->generateUrl('brother_guestbook_create_ajax'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Отправить отзыв'));

        return $form;
    }

    /**
     * Displays a form to create a new Guestbook entity.
     *
     */
    public function newDialogAction()
    {
        $entity = new Entry();
        $form = $this->createCreateForm($entity);
        $result = new BaseApi();

        return $this->ajaxResponse($result
            ->addRenderDom('body', array('appendModal' => $this->render('BrotherGuestbookBundle:Entry:_new_dialog.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            ))->getContent()))
            ->result());
    }

    /**
     * Displays a form to edit an existing Guestbook entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BrotherGuestbookBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guestbook entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BrotherGuestbookBundle:Guestbook:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Entry entity.
     *
     * @param Entry $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Entry $entity)
    {
        $form = $this->createForm(new GuestbookType(), $entity, array(
            'action' => $this->generateUrl('guestbook_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Guestbook entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BrotherGuestbookBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guestbook entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('guestbook_edit', array('id' => $id)));
        }

        return $this->render('BrotherGuestbookBundle:Guestbook:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Guestbook entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BrotherGuestbookBundle:Entry')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Guestbook entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('guestbook'));
    }

    /**
     * Creates a form to delete a Guestbook entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('guestbook_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    public function preExecute()
    {
        $this->configuration = new svModelGeneratorConfiguration('Guestbook', $this->getModuleName(), array(
            'fields' => array('name' => array('label' => 'Имя:')),
            'new' => array(
                'display' => array('q', 'name', 'email'),
            ),
            'form' => array(
                'actions' => array('_save_and_add' => array('label' => 'Отправить'))
            ),
            'list' => array(
                'title' => 'Ранее заданные вопросы(список)',
                'is_partial' => true,
                'layout' => 'stacked',
                'batch_actions' => false,
                'actions' => false,
                'object_actions' => false,
                'display' => array('q', 'a', 'created_at', 'updated_at'),
                'params' => '
			        <p class="p0" id="qqq%%id%%">
			            <strong>Вопрос</strong>: <span class="guestbook">%%q%%</span><br/>
			            <strong>Ответ</strong>: <span class="answer">%%a%%</span>
					</p>
				'
            ),
            'filter' => array(
                'class' => false
            ),
            'partials' => array('form_fieldset' => '', 'form_field' => '', 'breadcrumbs' => ''),
            'uri' => array('edit' => 'index', 'new' => 'index')
        ));
        parent::preExecute();
    }


    /**
     * Executes index action
     * @param \sfWebRequest $request
     *
     * @return string|void
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->form = new GuestbookForm();
        parent::executeIndex($request);
        parent::executeNew($request);
        if ($t = sfConfig::get('app_sv_guestbook_plugin_template_index', false)) {
            $this->setTemplate($t['template'], $t['module']);
        }
    }

    /**
     * @return Doctrine_Query
     */

    protected function buildQuery()
    {
        $query = parent::buildQuery();
        if (!sfConfig::get('app_sv_guestbook_plugin_enable_null_answer')) {
            $query->andWhere($query->getRootAlias() . '.a is not null')
                ->andWhere($query->getRootAlias() . '.a<>?', '');
        }
        return $query;
    }

    /**
     * Enter description here...
     *
     * @param sfWebRequest $request
     */

    public function executeCreate(sfWebRequest $request)
    {
        parent::executeCreate($request);
        parent::executeIndex($request);
        $t = sfConfig::get('app_sv_guestbook_plugin_template_index', array('module' => 'guestbook', 'template' => 'index'));
        $this->setTemplate($t['template'], $t['module']);
    }

    /**
     * shows login / signup form
     *
     */
    public function executeLast()
    {
        $this->guestbooks = dinCacheManager::getInstance()
            ->getContent('query', 'Guestbook',
                array(
                    'method' => 'createQueryLast',
                    'limit' => sfConfig::get('app_sv_guestbook_plugin_last_count')));
    }


    /**
     * Adds a new Entry/Show guestbook form.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->container->get('brother_guestbook.form_factory.entry');

        if ('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $entry = $form->getData();

                // save entry
                if ($this->getManager()->save($entry) !== false) {
                    $this->setFlashMessage('flash.save.success');

                    if(!$this->container->getParameter('brother_guestbook.auto_publish')) {
                        $this->setFlashMessage('flash.awaiting_approval');
                    }

                    // notify admin
                    if($this->container->getParameter('brother_guestbook.notify_admin')) {
                        $this->get('brother_guestbook.mailer')->sendAdminNotification($entry);
                    }

                    return $this->redirect($this->generateUrl('brother_guestbook_list'));
                } else {
                    $this->setFlashMessage('flash.error.bad_request', array(), 'error');
                }
            }
        }

        $view = $this->getView('frontend.new');

        return $this->render($view, array('form' => $form->createView()));
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
