<?php

namespace Brother\GuestbookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntryType extends AbstractType
{
    /**
     * @var string
     */
    private $entryClass;

    /**
     * Constructor
     *
     * @param string $entryClass
     */
    public function __construct($entryClass)
    {
        $this->entryClass = $entryClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'form.entry.name',
                'translation_domain' => 'BrotherGuestbookBundle'
            ))
            ->add('email', 'email', array(
                'label' => 'form.entry.email',
                'translation_domain' => 'BrotherGuestbookBundle',
            ))
            ->add('comment', 'textarea', array(
                'label' => 'form.entry.comment',
                'translation_domain' => 'BrotherGuestbookBundle',
            ))
            ->getForm();
	}

    /**
     * @param OptionsResolverInterface $resolver
     */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array('data_class' => $this->entryClass));
	}

    /**
     * @return string
     */
    public function getName()
    {
        return 'brother_guestbook_entry';
    }
}