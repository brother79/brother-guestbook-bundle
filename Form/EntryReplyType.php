<?php

namespace Brother\GuestbookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntryReplyType extends AbstractType
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
     * {@inheritdoc}
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $builder->add('email', 'email', array(
                'label' => 'form.reply.to',
                'translation_domain' => 'BrotherGuestbookBundle',
            ))
            ->add('senderEmail', 'email', array(
                'mapped' => false,
                'label' => 'form.reply.from',
                'translation_domain' => 'BrotherGuestbookBundle',
            ))
            ->add('title', 'text', array(
                'mapped' => false,
                'label' => 'form.reply.title',
                'translation_domain' => 'BrotherGuestbookBundle',
            ))
            ->add('message', 'textarea', array(
                'mapped' => false,
                'label' => 'form.reply.message',
                'translation_domain' => 'BrotherGuestbookBundle',
            ))
            ->getForm();
	}

    /**
     * {@inheritdoc}
     */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array('data_class' => $this->entryClass));
	}

    /**
     * {@inheritdoc}
     */
	public function getName()
	{
		return 'brother_guestbook_entry_reply';
	}
}