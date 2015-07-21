<?php

namespace Fenrizbes\FormConstructorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FcDateTimeType extends AbstractType
{
    public function getName()
    {
        return 'fc_datetime';
    }

    public function getParent()
    {
        return 'text';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'format'
        ));
    }
}