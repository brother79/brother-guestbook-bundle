<?php

namespace Brother\GuestbookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GuestbookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brother\GuestbookBundle\Entity\Guestbook'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brother_guestbookbundle_guestbook';
    }
}
