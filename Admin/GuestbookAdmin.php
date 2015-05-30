<?php

namespace Brother\GuestbookBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class GuestbookAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('q')
            ->add('a')
            ->add('email')
            ->add('executor')
            ->add('comment')
            ->add('priority')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('q')
            ->add('a')
            ->add('email')
            ->add('state')
			->add('created_at')
			->add('updated_at')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', array('class' => 'col-md-6'))
            ->add('name')
            ->add('q')
            ->add('a')
            ->add('email')
            ->end()
            ->with('Other', array('class' => 'col-md-6'))
            ->add('executor')
            ->add('comment')
            ->add('priority')
            ->add('state')
            ->add('created_at', 'sonata_type_datetime_picker')
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('q')
            ->add('a')
            ->add('email')
            ->add('executor')
            ->add('comment')
            ->add('priority')
            ->add('created_at')
            ->add('updated_at')
        ;
    }
}
