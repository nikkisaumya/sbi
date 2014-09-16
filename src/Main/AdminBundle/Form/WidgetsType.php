<?php

namespace Main\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WidgetsType extends AbstractType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\AdminBundle\Entity\Widgets'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'main_adminbundle_widgets';
    }
}
